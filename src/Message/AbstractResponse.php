<?php

namespace Omnipay\PayPalStandard\Message;

use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Response
 */
abstract class AbstractResponse extends OmnipayAbstractResponse implements RedirectResponseInterface
{
    protected const TEST_ENDPOINT = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    protected const LIVE_ENDPOINT = 'https://www.paypal.com/cgi-bin/webscr';

    public function getEndpoint(): string
    {
        return $this->getRequest()->getTestMode() ? self::TEST_ENDPOINT : self::LIVE_ENDPOINT;
    }

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
    }

    public function getRedirectUrl()
    {
        return $this->getEndpoint() . '?' . http_build_query($this->data);
    }

    /**
     * Should the browser redirect using GET or POST
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return $this->getData();
    }
}
