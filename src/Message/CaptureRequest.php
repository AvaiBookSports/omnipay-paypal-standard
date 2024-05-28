<?php

namespace Omnipay\PayPalStandard\Message;

/**
 * Capture Request
 */
class CaptureRequest extends AuthorizeRequest
{
    public function getTransactionType()
    {
        return 'capture';
    }
}
