<?php
/**
 * Omnipay class
 */

namespace Omnipay\PaypalStandard;

use Omnipay\Common\AbstractGateway;

/**
 * PayPal Standard Gateway.
 */
class StandardGateway extends AbstractGateway
{
    public function getName()
    {
        return 'PayPal Standard';
    }

    public function getDefaultParameters()
    {
        return array(
            'MerchantAccountEmail' => '',
            'testMode' => false,
        );
    }

    /**
     *
     * @param array $parameters
     * @return Message\AuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest(Message\AuthorizeRequest::class, $parameters);
    }

    /**
     *
     * @param array $parameters
     * @return Message\CaptureRequest
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest(Message\CaptureRequest::class, $parameters);
    }

    /**
     *
     * @param array $parameters
     * @return Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest(Message\PurchaseRequest::class, $parameters);
    }

    /**
     *
     * @param array $parameters
     * @return Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(Message\CompletePurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return Message\CompleteAuthorizeRequest
     */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest(Message\CompleteAuthorizeRequest::class, $parameters);
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getMerchantAccountEmail()
    {
        return $this->getParameter('MerchantAccountEmail');
    }

    public function setMerchantAccountEmail($value)
    {
        return $this->setParameter('MerchantAccountEmail', $value);
    }
}
