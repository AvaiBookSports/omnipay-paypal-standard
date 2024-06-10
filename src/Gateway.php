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
            'business' => '',
            'no_note' => 1,
            'testMode' => false,
            'noVerifyIpn' => false,
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

    public function getNoVerifyIpn(): bool
    {
        return $this->getParameter('noVerifyIpn');
    }

    public function setNoVerifyIpn(bool $value)
    {
        return $this->setParameter('noVerifyIpn', $value);
    }

    public function getBusiness()
    {
        return $this->getParameter('business');
    }

    public function setBusiness($value)
    {
        return $this->setParameter('business', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getRM()
    {
        return $this->getParameter('rm');
    }

    /**
     * @param int $value
     * 0 -> GET
     * 1 -> GET but no payment variables are included
     * 2 -> POST and all payment variables are included
     */
    public function setRM(int $value)
    {
        return $this->setParameter('rm', $value);
    }

    public function getCbt()
    {
        return $this->getParameter('cbt');
    }

    public function setCbt($value)
    {
        return $this->setParameter('cbt', $value);
    }

    public function getCmd()
    {
        return $this->getParameter('cmd');
    }

    /**
     * @param string $value
     * _xclick
     * _cart
     * _xclick-subscriptions
     * _xclick-auto-billing
     * _donations
     * _s-xclick
     */
    public function setCmd(string $value)
    {
        return $this->setParameter('cmd', $value);
    }

    public function getNoShipping()
    {
        return $this->getParameter('no_shipping');
    }

    /**
     * @param int $value
     * 0 -> Prompt for an address, but do not require one
     * 1 -> Do not prompt for an address
     * 2 -> Prompt for an address and require one
     */
    public function setNoShipping(int $value)
    {
        return $this->setParameter('no_shipping', $value);
    }

    public function getItemNumber()
    {
        return $this->getParameter('item_number');
    }

    public function setItemNumber(string $value)
    {
        return $this->setParameter('item_number', $value);
    }

    public function getItemName()
    {
        return $this->getParameter('item_name');
    }

    public function setItemName(string $value)
    {
        return $this->setParameter('item_name', $value);
    }

    public function getLc()
    {
        return $this->getParameter('lc');
    }

    public function setLc(string $value)
    {
        return $this->setParameter('lc', $value);
    }

    public function getCustom()
    {
        return $this->getParameter('custom');
    }

    /**
     * Pass-through variable for your own tracking purposes, which buyers do not see
     * @param string $value
     */
    public function setCustom(string $value)
    {
        return $this->setParameter('custom', $value);
    }

    public function getNotifyUrl()
    {
        return $this->getParameter('notify_url');
    }

    public function setNotifyUrl(string $value)
    {
        return $this->setParameter('notify_url', $value);
    }

    public function getCancelReturn()
    {
        return $this->getParameter('cancel_return');
    }

    public function setCancelReturn(string $value)
    {
        return $this->setParameter('cancel_return', $value);
    }

    public function getReturn()
    {
        return $this->getParameter('return');
    }

    public function setReturn(string $value)
    {
        return $this->setParameter('return', $value);
    }
}
