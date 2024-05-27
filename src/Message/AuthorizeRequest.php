<?php

namespace Omnipay\PaypalStandard\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * PayPal Standard Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{

    public function getData()
    {
        foreach ($this->getRequiredCoreFields() as $field) {
            $this->validate($field);
        }
        $this->validateCardFields();
        $data = $this->getBaseData() + $this->getTransactionData() + $this->getURLData();
        return $data;
    }


    /**
     * sendData function. In this case, where the browser is to be directly it constructs and returns a response object
     * @param mixed $data
     * @return ResponseInterface|AuthorizeResponse
     */
    public function sendData($data)
    {
        return $this->response = new AuthorizeResponse($this, $data);
    }

    protected function createResponse($data)
    {
        return $this->response = new AuthorizeResponse($this, $data);
    }

    public function getSite()
    {
        return $this->getParameter('site');
    }

    public function setSite($value)
    {
        return $this->setParameter('site', $value);
    }

    public function getMerchantAccountEmail()
    {
        return $this->getParameter('MerchantAccountEmail');
    }

    public function setMerchantAccountEmail($value)
    {
        return $this->setParameter('MerchantAccountEmail', $value);
    }

    /**
     * Get an array of the required fields for the core gateway
     * @return array
     */
    public function getRequiredCoreFields()
    {
        return array
        (
            'amount',
            'currency',
        );
    }

    /**
     * get an array of the required 'card' fields (personal information fields)
     * @return array
     */
    public function getRequiredCardFields()
    {
        return array
        (
            'email',
        );
    }

    /**
     * Get values for IPN and browser return urls.
     *
     * Browser return urls should all be set or non set.
     *
     * https://developer.paypal.com/docs/classic/ipn/integration-guide/IPNandPDTVariables/
     */
    public function getURLData()
    {
        $data = array();
        $data['ipn_notification_url'] = urlencode($this->getNotifyUrl());
        $data['return_url'] = $this->getReturnUrl();
        $data['cancel_return'] = $this->getCancelUrl();
        return $data;
    }

    /**
     * @return string
     */
    public function getUniqueID()
    {
        return uniqid();
    }

    /**
     * Map Omnipay normalised fields to gateway defined fields. If the order the fields are
     * passed to the gateway matters you should order them correctly here
     *
     * @return array
     * @throws InvalidRequestException
     */

    public function getTransactionData()
    {
        return array
        (
            'amount' => $this->getAmount(),
            'currency_code' => $this->getCurrency(),
            'email' => $this->getCard()->getEmail(),
            'transaction_id' => $this->getTransactionId(),
            'item_name' => $this->getDescription(),
        );
    }

    /**
     * @return array
     * Get data that is common to all requests - generally aut
     */
    public function getBaseData()
    {
        return array(
            'business' => $this->getMerchantAccountEmail(),
        );
    }

    public function getTransactionType()
    {
        return 'Authorize';
    }

    public function getPaymentMethod()
    {
        // ???
        // return 'card';
        // return $this->getParameter('paymentMethod');
    }
}
