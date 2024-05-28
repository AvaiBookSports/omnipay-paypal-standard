<?php

namespace Omnipay\PayPalStandard;

use Omnipay\Common\AbstractGateway;

/**
 * PayPal Standard Gateway.
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PayPal Standard';
    }

    public function getDefaultParameters()
    {
        return [
            'businessEmail' => '',
            'testMode' => false,
        ];
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

    public function getBusinessEmail()
    {
        return $this->getParameter('businessEmail');
    }

    public function setBusinessEmail($value)
    {
        return $this->setParameter('businessEmail', $value);
    }

    public function getCmd()
    {
        return $this->getParameter('cmd');
    }

    /**
     * @param string $cmd
     * _xclick
     * _cart
     * _xclick-subscriptions
     * _xclick-auto-billing
     * _donations
     * _s-xclick
     * @return Gateway
     */
    public function setCmd(string $cmd)
    {
        return $this->setParameter('cmd', $cmd);
    }

    public function getReturnMethod()
    {
        return $this->getParameter('rm');
    }

    /**
     * @param int $returnMethod
     * 0 -> GET
     * 1 -> GET but no payment variables are included
     * 2 -> POST and all payment variables are included
     * @return Gateway
     */
    public function setReturnMethod(int $returnMethod)
    {
        return $this->setParameter('rm', $returnMethod);
    }

    public function getNoShipping()
    {
        return $this->getParameter('no_shipping');
    }

    /**
     * @param int $noShipping
     * 0 -> Prompt for an address, but do not require one
     * 1 -> Do not prompt for an address
     * 2 -> Prompt for an address and require one
     * @return Gateway
     */
    public function setNoShipping(int $noShipping)
    {
        return $this->setParameter('no_shipping', $noShipping);
    }

    public function getReturnMerchantButtonText()
    {
        return $this->getParameter('cbt');
    }

    public function setReturnMerchantButtonText(string $returnMerchantButtonText)
    {
        return $this->setParameter('cbt', $returnMerchantButtonText);
    }

    public function getPrivateOrderId()
    {
        return $this->getParameter('item_number');
    }

    public function setPrivateOrderId(string $privateOrderId)
    {
        return $this->setParameter('item_number', $privateOrderId);
    }

    public function getLocale()
    {
        return $this->getParameter('lc');
    }

    public function setLocale(string $locale)
    {
        return $this->setParameter('lc', $locale);
    }

    public function getCustomData()
    {
        return $this->getParameter('custom');
    }

    /**
     * Pass-through variable for your own tracking purposes, which buyers do not see
     * @param string $customData
     * @return Gateway
     */
    public function setCustomData(string $customData)
    {
        return $this->setParameter('custom', $customData);
    }
}
