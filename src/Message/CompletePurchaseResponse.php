<?php

namespace Omnipay\PayPalStandard\Message;

use Omnipay\Common\Http\Client;
use Omnipay\Common\Message\RequestInterface;

class CompletePurchaseResponse extends AbstractResponse
{
    private const SANDBOX_IPN = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

    private const LIVE_IPN = 'https://ipnpb.paypal.com/cgi-bin/webscr';

    /**
     * @var non-empty-string|null
     */
    private ?string $paymentStatus = null;

    private Client $client;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->client = new Client();

        if (!empty($this->data['payment_status'])) {
            $this->paymentStatus = (string)$this->data['payment_status'];
        }
    }

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

    public function isPending()
    {
        if (!$this->verifyIpn()) {
            return false;
        }

        if ('Pending' === $this->paymentStatus) {
            return true;
        }

        return parent::isPending();
    }

    public function isSuccessful()
    {
        if (!$this->verifyIpn()) {
            return false;
        }

        return 'Completed' === $this->paymentStatus;
    }

    public function isCancelled()
    {
        if (!$this->verifyIpn()) {
            return false;
        }

        $cancelledStatuses = [
            'Denied',
            'Expired',
            'Failed',
            'Voided',
        ];

        return in_array($this->paymentStatus, $cancelledStatuses, true);
    }

    private function verifyIpn()
    {
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);

        $myPost = [];
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                // Since we do not want the plus in the datetime string to be encoded to a space, we manually encode it.
                if ($keyval[0] === 'payment_date') {
                    if (substr_count($keyval[1], '+') === 1) {
                        $keyval[1] = str_replace('+', '%2B', $keyval[1]);
                    }
                }
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }

        if (empty($myPost['ipn_track_id'])) {
            return false;
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

        $response = $this->client
            ->request(
                'POST',
                $this->getIpnUrl(),
                [
                    'User-Agent' => 'PHP-IPN-Verification-Script',
                    'Connection' => 'Close',
                ],
                $req
            )
            ->getBody()
            ->getContents();

        return 'VERIFIED' === $response;
    }

    public function getTransactionReference()
    {
        return $this->data['payer_id'];
    }
}