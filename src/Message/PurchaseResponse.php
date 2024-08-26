<?php

namespace Omnipay\PayPalStandard\Message;

/**
 * Complete Authorize Response
 */
class PurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }
}
