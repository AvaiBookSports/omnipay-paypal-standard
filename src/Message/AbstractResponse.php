<?php

declare(strict_types=1);

namespace Omnipay\PayPalStandard\Message;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    private const SANDBOX_IPN = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

    private const LIVE_IPN = 'https://ipnpb.paypal.com/cgi-bin/webscr';

    protected function isTestMode(): bool
    {
        return $this->request->getParameters()['testMode'];
    }

    protected function getIpnUrl(): string
    {
        if ($this->isTestMode()) {
            return self::SANDBOX_IPN;
        }

        return self::LIVE_IPN;
    }

    public function isSuccessful()
    {
        if (
            empty($this->data)
            || empty($this->data['payment_status'])
            || 'Completed' !== $this->data['payment_status']
            || !count($_POST)
        ) {
            return false;
        }

        return $this->verifyIpn();
    }

    public function isRedirect()
    {
        return $this->isPending();
    }

    public function isPending()
    {
        if (
            !empty($this->data)
            && !empty($this->data['payment_status'])
            && 'Pending' === $this->data['payment_status']
        ) {
            return true;
        }

        return parent::isPending();
    }

    private function verifyIpn()
    {
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = [];
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (!empty($keyval) && 2 == count($keyval)) {
                // Since we do not want the plus in the datetime string to be encoded to a space, we manually encode it.
                if ('payment_date' === $keyval[0]) {
                    if (1 === substr_count($keyval[1], '+')) {
                        $keyval[1] = str_replace('+', '%2B', $keyval[1]);
                    }
                }
                $myPost[$keyval[0]] = rawurldecode($keyval[1]);
            }
        }
        // Build the body of the verification post request, adding the _notify-validate command.
        $req = 'cmd=_notify-validate';

        foreach ($myPost as $key => $value) {
            $value = rawurlencode($value);
            $req .= "&$key=$value";
        }

        $ch = curl_init($this->getIpnUrl());
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: PHP-IPN-Verification-Script',
            'Connection: Close',
        ]);

        $res = curl_exec($ch);

        if (!$res) {
            $errno = curl_errno($ch);
            $errstr = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: [$errno] $errstr");
        }

        $info = curl_getinfo($ch);
        $http_code = $info['http_code'];

        if (200 != $http_code) {
            throw new Exception("PayPal responded with http code $http_code");
        }

        curl_close($ch);

        // Check if PayPal verifies the IPN data, and if so, return true.
        if ($res == 'VERIFIED') {
            return true;
        } else {
            return false;
        }
    }

    public function isCancelled()
    {
        if (
            empty($this->data)
            || empty($this->data['payment_status'])
            || !in_array($this->data['payment_status'], ['Completed', 'Pending'])
            || !count($_POST)
        ) {
            return true;
        }

        return false;
    }

    public function getTransactionReference()
    {
        return $this->data['payer_id'];
    }
}
