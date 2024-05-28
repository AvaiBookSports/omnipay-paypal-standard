<?php

namespace Omnipay\PayPalStandard\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Authorize.Net SIM Complete Authorize Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        $payerID = $this->data['PayerID'];
        // TODO: Verify IPN

        // return isset($this->data['is_success']) && '1' === $this->data['is_success']
        return true;
    }
}
