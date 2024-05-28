<?php

namespace Omnipay\PayPalStandard\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Response
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    protected string $testEndpoint = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    protected string $liveEndpoint = 'https://www.paypal.com/cgi-bin/webscr';

    public function getEndpoint(): string
    {
        return $this->getRequest()->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    /**
     * Has the call to the processor succeeded?
     * When we need to redirect the browser we return false as the transaction is not yet complete
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Should the user's browser be redirected?
     *
     * @return bool
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Transparent redirect is the mode whereby a form is presented to the user that POSTs to the payment
     * processor site directly. If this returns true the site will need to provide a form for this
     *
     * @return bool
     */
    public function isTransparentRedirect()
    {
        return false;
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
        return 'GET'; // POST?
    }

    public function getRedirectData()
    {
        return $this->getData();
    }
}
