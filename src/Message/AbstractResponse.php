<?php

declare(strict_types=1);

namespace Omnipay\PayPalStandard\Message;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    /**
     * https://www.youtube.com/watch?v=dQw4w9WgXcQ
     */
    private const SANDBOX_IPN = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

    private const LIVE_IPN = 'https://ipnpb.paypal.com/cgi-bin/webscr';

    protected function isTestMode(): bool
    {
        return $this->request->getParameters()['testMode'];
    }

    protected function noVerifyIpn(): bool
    {
        return $this->request->getParameters()['noVerifyIpn'];
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
            || empty($this->data['payer_status'])
            || 'VERIFIED' !== $this->data['payer_status']
            || !count($_POST)
        ) {
            return false;
        }

        parse_str(file_get_contents("php://input"), $raw_post_array);

        foreach ($raw_post_array as $key => $value) {
            if ($key === 'payment_date') {
                if (substr_count($value, '+') === 1) {
                    $value = str_replace('+', '%2B', $value);
                }
            }
            $myPost[$key] = urldecode($value);
        }

        // Build the body of the verification post request, adding the _notify-validate command.
        $req = 'cmd=_notify-validate';
        $get_magic_quotes_exists = false;
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }

            $req .= "&$key=$value";
        }

        // Post the data back to PayPal, using curl. Throw exceptions if errors occur.
        $ch = curl_init($this->getIpnUrl());
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, htmlspecialchars($req));
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: PHP-IPN-Verification-Script',
            'Connection: Close',
        ));
        $res = curl_exec($ch);
        if (!($res)) {
            $errno = curl_errno($ch);
            $errstr = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: [$errno] $errstr");
        }

        $info = curl_getinfo($ch);
        $http_code = $info['http_code'];
        if ($http_code != 200) {
            throw new Exception("PayPal responded with http code $http_code");
        }

        curl_close($ch);

        // TODO: Apparently in test mode always returns INVALID
        if ($this->isTestMode() || $this->noVerifyIpn()) {
            return true;
        }

        // Check if PayPal verifies the IPN data, and if so, return true.
        if ($res == 'VERIFIED') {
            return true;
        } else {
            return false;
        }
    }
}