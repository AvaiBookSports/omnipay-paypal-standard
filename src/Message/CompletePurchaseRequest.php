<?php

namespace Omnipay\PaypalStandard\Message;

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
        // TODO: Check this lines
        //if (strtolower($this->httpRequest->request->get('x_MD5_Hash')) !== $this->getHash()) {
        //    throw new InvalidRequestException('Incorrect hash');
        //}

        return $this->httpRequest->request->all();
    }
}
