<?php

namespace App\Service\Payment;

use App\Service\Payment\Exception\PaymentException;

class LiqPayAPI
{
    const CURRENCY_EUR = 'EUR';
    const CURRENCY_USD = 'USD';
    const CURRENCY_UAH = 'UAH';

    private string $apiUrl = 'https://www.liqpay.ua/api/';
    private string $checkoutUrl = 'https://www.liqpay.ua/api/3/checkout';
    protected array $supportedCurrencies = [
        self::CURRENCY_EUR,
        self::CURRENCY_USD,
        self::CURRENCY_UAH,
    ];
    private string $publicKey;
    private string $privateKey;
    private ?int $serverResponseCode = null;

    public function __construct(string $paymentPublicKey, string $paymentPrivateKey)
    {
        $this->publicKey = $paymentPublicKey;
        $this->privateKey = $paymentPrivateKey;
    }

    public function api($path, $params = [], $timeout = 5) : array
    {
        if (!isset($params['version'])) {
            throw new \InvalidArgumentException('version is null');
        }
        $url         = $this->apiUrl . $path;
        $public_key  = $this->publicKey;
        $private_key = $this->privateKey;
        $data        = $this->encodeParams(array_merge(compact('public_key'), $params));
        $signature   = $this->strToSign($private_key.$data.$private_key);
        $postfields  = http_build_query([
            'data'  => $data,
            'signature' => $signature
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Avoid MITM vulnerability http://phpsecurity.readthedocs.io/en/latest/Input-Validation.html#validation-of-input-sources
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);    // Check the existence of a common name and also verify that it matches the hostname provided
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,$timeout);   // The number of seconds to wait while trying to connect
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);          // The maximum number of seconds to allow cURL functions to execute
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $this->serverResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return json_decode($server_output, true);
    }

    public function getResponseCode() : ?int
    {
        return $this->serverResponseCode;
    }

    public function getFormParams(array $params) : array
    {
        $params    = $this->cnbParams($params);

        return [
            'action' => $this->checkoutUrl,
            'data' => $this->encodeParams($params),
            'signature' => $this->cnbSignature($params)
        ];
    }


    public function cnbSignature($params) : string
    {
        $private_key = $this->privateKey;
        $params      = $this->cnbParams($params);
        $data        = $this->encodeParams($params);
        return $this->strToSign($private_key . $data . $private_key);
    }

    private function cnbParams($params) : array
    {
        $params['public_key'] = $this->publicKey;

        if (!isset($params['version'])) {
            throw new PaymentException('version is null');
        }
        if (!isset($params['amount'])) {
            throw new PaymentException('amount is null');
        }
        if (!isset($params['currency'])) {
            throw new PaymentException('currency is null');
        }
        if (!in_array($params['currency'], $this->supportedCurrencies)) {
            throw new PaymentException(sprintf('currency %s is not supported', $params['currency']));
        }

        if (!isset($params['description'])) {
            throw new PaymentException('description is null');
        }

        return $params;
    }

    private function encodeParams(array $params) : string
    {
        return base64_encode(json_encode($params));
    }

    public function decodeParams(string $params) : array
    {
        return json_decode(base64_decode($params), true);
    }

    public function strToSign(string $str) : string
    {
        return base64_encode(sha1($str, 1));
    }
}
