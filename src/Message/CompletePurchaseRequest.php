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
        return array_merge($this->httpRequest->query->all(), $this->httpRequest->request->all());
    }
}
