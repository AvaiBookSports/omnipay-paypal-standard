<?php

namespace Omnipay\PayPalStandard\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        foreach ($this->getRequiredCoreFields() as $field) {
            $this->validate($field);
        }
        return array_merge($this->getBaseData(), $this->getTransactionData());
    }

    public function getTransactionType()
    {
        return $this->getParameter('transactionType');
    }

    public function setTransactionType($value)
    {
        return $this->setParameter('transactionType', $value);
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

    public function setCmd($value)
    {
        return $this->setParameter('cmd', $value);
    }

    public function getReturnMethod()
    {
        return $this->getParameter('rm');
    }

    public function setReturnMethod($value)
    {
        return $this->setParameter('rm', $value);
    }

    public function getNoShipping()
    {
        return $this->getParameter('no_shipping');
    }

    public function setNoShipping($value)
    {
        return $this->setParameter('no_shipping', $value);
    }

    public function getReturnMerchantButtonText()
    {
        return $this->getParameter('cbt');
    }

    public function setReturnMerchantButtonText($value)
    {
        return $this->setParameter('cbt', $value);
    }

    public function getPrivateOrderId()
    {
        return $this->getParameter('item_number');
    }

    public function setPrivateOrderId($value)
    {
        return $this->setParameter('item_number', $value);
    }

    public function getLocale()
    {
        return $this->getParameter('lc');
    }

    public function setLocale($value)
    {
        return $this->setParameter('lc', $value);
    }

    public function getCustomData()
    {
        return $this->getParameter('custom');
    }

    public function setCustomData($value)
    {
        return $this->setParameter('custom', $value);
    }
}
