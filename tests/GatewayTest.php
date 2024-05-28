<?php

declare(strict_types=1);

namespace Omnipay\PayPalStandard;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\GatewayTestCase;

final class GatewayTest extends GatewayTestCase
{
    private Gateway $standardGateway;

    public function setUp(): void
    {
        parent::setUp();

        $this->standardGateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->standardGateway->setBusinessEmail('testing@testing.test');
    }

    /**
     * @return iterable<string, array{
     *     amount: string,
     *     currency: string,
     *     function: string
     * }>
     */
    public function gatewayDataProvider(): iterable
    {
        yield 'Complete purchase successfully' => [
            'amount' => '10.00',
            'currency' => 'EUR',
            'function' => 'purchase'
        ];
        yield 'Complete authorize successfully' => [
            'amount' => '10.00',
            'currency' => 'EUR',
            'function' => 'authorize'
        ];
        yield 'Complete capture successfully' => [
            'amount' => '10.00',
            'currency' => 'EUR',
            'function' => 'capture'
        ];
    }

    /**
     * @test
     * @testdox it should assert each case
     * @dataProvider gatewayDataProvider
     * @throws InvalidRequestException
     */
    public function it_should_assert_each_case(string $amount, string $currency, string $function): void
    {
        $gateway = new gateway($this->getHttpClient(), $this->getHttpRequest());
        $gateway->setBusinessEmail('testing@testing.test');

        $response = $gateway->$function(['amount' => $amount, 'currency' => $currency])->send();

        $this->assertFalse($response->isSuccessful()); // Because purchase is not complete
        $this->assertTrue($response->isRedirect());
        $this->assertNotEmpty($response->getRedirectUrl());
        $this->assertStringContainsString(
            sprintf(
                'https://www.paypal.com/cgi-bin/webscr?business=%s&amount=%s&currency_code=%s', // &item_number=&cmd=&bn=&no_note=&no_shipping=&rm=&lc=&custom=&cbt=
                urlencode('testing@testing.test'),
                $amount,
                $currency
            ),
            $response->getRedirectUrl()
        );
        $this->assertFalse($response->isTransparentRedirect());

        $request = $gateway->completePurchase(['amount' => $amount, 'currency' => $currency]);

        $this->assertInstanceOf(Message\CompletePurchaseRequest::class, $request);
        $this->assertSame($amount, $request->getAmount());
    }

    /**
     * @throws InvalidRequestException
     */
    public function testCompletePurchase()
    {
        $request = $this->standardGateway->completePurchase(['amount' => '10.00']);

        $this->assertInstanceOf(Message\CompletePurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    /**
     * @test
     * @testdox it should throw invalid request exception sending purchase without amount
     */
    public function it_should_throw_invalid_request_exception_sending_purchase_without_amount(): void
    {
        $this->expectException(InvalidRequestException::class);

        $this->standardGateway->purchase(
            [
                'currency' => 'EUR',
            ]
        )->send();
    }

    /**
     * @test
     * @testdox it should throw invalid request exception sending purchase without currency
     */
    public function it_should_throw_invalid_request_exception_sending_purchase_without_currency(): void
    {
        $this->expectException(InvalidRequestException::class);

        $this->standardGateway->purchase(
            [
                'amount' => '10.00',
            ]
        )->send();
    }
}