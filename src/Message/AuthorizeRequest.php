<?php

namespace Omnipay\PayPalStandard\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * PayPal Standard Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $data = parent::getData();

        $data['notify_url'] = $this->getNotifyUrl();
        $data['return'] = $this->getReturn();
        $data['cancel_return'] = $this->getCancelReturn();
        $data['business'] = $this->getBusiness();

        return $data;
    }

    /**
     * sendData function. In this case, where the browser is to be directly it constructs and returns a response object
     * @param mixed $data
     * @return ResponseInterface|AuthorizeResponse
     */
    public function sendData($data)
    {
        return $this->response = new AuthorizeResponse($this, $data);
    }

    protected function createResponse($data)
    {
        return $this->response = new AuthorizeResponse($this, $data);
    }
}
