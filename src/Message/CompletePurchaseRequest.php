<?php

namespace Omnipay\PayPalStandard\Message;

/**
 * Authorize Request
 * CompletePurchaseRequest.php - processes the IPN
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    public function getData()
    {
        $data = parent::getData();

        return array_merge($data, $this->httpRequest->query->all(), $this->httpRequest->request->all());
    }
}
