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

    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function getBusiness()
    {
        return $this->getParameter('business');
    }

    public function setBusiness($value)
    {
        return $this->setParameter('business', $value);
    }

    public function getRM()
    {
        return $this->getParameter('rm');
    }

    public function setRM($value)
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

    public function setCmd($value)
    {
        return $this->setParameter('cmd', $value);
    }

    public function getNoShipping()
    {
        return $this->getParameter('no_shipping');
    }

    public function setNoShipping($value)
    {
        return $this->setParameter('no_shipping', $value);
    }

    public function getItemNumber()
    {
        return $this->getParameter('item_number');
    }

    public function setItemNumber($value)
    {
        return $this->setParameter('item_number', $value);
    }

    public function getItemName()
    {
        return $this->getParameter('item_name');
    }

    public function setItemName($value)
    {
        return $this->setParameter('item_name', $value);
    }

    public function getLc()
    {
        return $this->getParameter('lc');
    }

    public function setLc($value)
    {
        return $this->setParameter('lc', $value);
    }

    public function getCustom()
    {
        return $this->getParameter('custom');
    }

    public function setCustom($value)
    {
        return $this->setParameter('custom', $value);
    }

    public function getNotifyUrl()
    {
        return $this->getParameter('notify_url');
    }

    public function setNotifyUrl($value)
    {
        return $this->setParameter('notify_url', $value);
    }

    public function getCancelReturn()
    {
        return $this->getParameter('cancel_return');
    }

    public function setCancelReturn($value)
    {
        return $this->setParameter('cancel_return', $value);
    }

    public function getReturn()
    {
        return $this->getParameter('return');
    }

    public function setReturn($value)
    {
        return $this->setParameter('return', $value);
    }

    public function getNoNote()
    {
        return $this->getParameter('no_note');
    }

    public function setNoNote($value)
    {
        return $this->setParameter('no_note', $value);
    }
}
