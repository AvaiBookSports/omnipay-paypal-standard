<?php

namespace Omnipay\PayPalStandard\Message;

/**
 * Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
