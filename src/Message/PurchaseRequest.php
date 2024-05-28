<?php

namespace Omnipay\PayPalStandard\Message;

/**
 * Purchase Request
 */
class PurchaseRequest extends AuthorizeRequest
{
    public function getTransactionType()
    {
        return 'sale';
    }
}
