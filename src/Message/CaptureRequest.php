<?php

namespace Omnipay\PayPalStandard\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * Capture Request
 */
class CaptureRequest extends AuthorizeRequest
{
    public function getData()
    {
        foreach ($this->getRequiredCoreFields() as $field) {
            $this->validate($field);
        }
        return array_merge($this->httpRequest->query->all(), $this->httpRequest->request->all());
    }

    /**
     * sendData function. In this case, where the browser is to be directly it constructs and returns a response object
     * @param mixed $data
     * @return ResponseInterface|CaptureResponse
     */
    public function sendData($data)
    {
        return $this->response = new CaptureResponse($this, $data);
    }

    /**
     * Get an array of the required fields for the core gateway
     */
    public function getRequiredCoreFields(): array
    {
        return [
            'transactionReference',
            'amount',
            'currency'
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
            'payer_id' => $this->getTransactionReference(),
        ];
    }

    public function getTransactionType()
    {
        return 'capture';
    }
}
