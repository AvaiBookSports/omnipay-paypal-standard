<?php

declare(strict_types=1);

namespace Omnipay\PaypalStandard;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\GatewayTestCase;

final class StandardGatewayTest extends GatewayTestCase
{
    private StandardGateway $standardGateway;

    public function setUp(): void
    {
        parent::setUp();

        $this->standardGateway = new StandardGateway($this->getHttpClient(), $this->getHttpRequest());
        $this->standardGateway->setUsername('John');
        $this->standardGateway->setPassword('Testing');
    }

    /**
     * @return iterable<string, array{
     *     amount: string,
     *     currency: string,
     *     function: string,
     *     creditCard: CreditCard
     * }>
     */
    public function standardGatewayDataProvider(): iterable
    {
        yield 'Complete purchase successfully' => [
            'amount' => '10.00',
            'currency' => 'EUR',
            'function' => 'purchase',
            'creditCard' => new CreditCard(['email' => 'mail@mail.com'])
        ];
        yield 'Complete authorize successfully' => [
            'amount' => '10.00',
            'currency' => 'EUR',
            'function' => 'authorize',
            'creditCard' => new CreditCard(['email' => 'mail@mail.com'])
        ];
        yield 'Complete capture successfully' => [
            'amount' => '10.00',
            'currency' => 'EUR',
            'function' => 'capture',
            'creditCard' => new CreditCard(['email' => 'mail@mail.com'])
        ];
    }

    /**
     * @test
     * @testdox it should assert each case
     * @dataProvider standardGatewayDataProvider
     */
    public function it_should_assert_each_case(string $amount, string $currency, string $function, CreditCard $creditCard): void
    {
        $standardGateway = new StandardGateway($this->getHttpClient(), $this->getHttpRequest());
        $standardGateway->setUsername('John');
        $standardGateway->setPassword('Testing');

        $response = $standardGateway->$function(['amount' => $amount, 'currency' => $currency, 'card' => $creditCard])->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNotEmpty($response->getRedirectUrl());
        $this->assertSame(
            sprintf(
                'https://www.paypal.com/cgi-bin/webscr?business=&amount=%s&currency_code=%s&email=%s&ipn_notification_url=&bn=CiviCRM_SP&cmd=_xclick',
                $amount,
                $currency,
                urlencode($creditCard->getEmail())
            ),
            $response->getRedirectUrl()
        );
        $this->assertFalse($response->isTransparentRedirect());
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

    public function testCompletePurchaseSendMissingEmail()
    {
        $this->expectException(InvalidRequestException::class);

        $this->standardGateway->purchase(
            [
                'amount' => '10.00',
                'currency' => 'EUR',
                'card' => new CreditCard(
                    [
                        'firstName' => 'John',
                        'lastName' => 'Testing'
                    ]
                )
            ]
        )->send();
    }

    public function testCompletePurchaseSendNonCreditCardObject()
    {
        $this->expectException(InvalidRequestException::class);

        $this->standardGateway->purchase(
            [
                'amount' => '10.00',
                'currency' => 'EUR',
                'card' => [
                    'firstName' => 'John',
                    'lastName' => 'Testing',
                ]
            ]
        )->send();
    }
}