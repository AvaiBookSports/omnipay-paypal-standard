<?php

namespace Omnipay\PaypalStandard\Message;

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
