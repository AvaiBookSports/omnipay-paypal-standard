<?php

namespace Omnipay\PayPalStandard\Message;

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
        return array_merge($this->getBaseData(), $this->getTransactionData(), $this->getURLData());
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

    /**
     * Get an array of the required fields for the core gateway
     */
    public function getRequiredCoreFields(): array
    {
        return [
            'amount',
            'currency'
        ];
    }

    /**
     * Get values for IPN and browser return urls.
     *
     * Browser return urls should all be set or non set.
     */
    public function getURLData(): array
    {
        return [
            'notify_url' => $this->getNotifyUrl(),
            'return' => $this->getReturn(),
            'cancel_return' => $this->getCancelReturn()
        ];
    }

    /**
     * Map Omnipay normalised fields to gateway defined fields. If the order fields are
     * passed to the gateway matters you should order them correctly here
     *
     * 
     *https://developer.paypal.com/api/nvp-soap/paypal-payments-standard/integration-guide/Appx-websitestandard-htmlvariables/
     * @throws InvalidRequestException
     */
    public function getTransactionData(): array
    {
        return [
            'amount' => $this->getAmount(),
            'currency_code' => $this->getCurrency(),
            'transaction_id' => $this->getTransactionId(),
            'item_name' => $this->getItemName(),
            'item_number' => $this->getItemNumber(),
            'cmd' => $this->getCmd(),
            'no_note' => $this->getNoNote(), // Do not prompt buyers to include a note with their payments
            'no_shipping' => $this->getNoShipping(),
            'rm' => $this->getRM(),
            'lc' => $this->getLc(),
            'custom' => $this->getCustom(),
            'cbt' => $this->getCbt(),
        ];
    }

    /**
     * @return array
     * Get data that is common to all requests
     */
    public function getBaseData(): array
    {
        return [
            'business' => $this->getBusiness(),
        ];
    }

    public function getTransactionType()
    {
        return 'Authorize';
    }
}
