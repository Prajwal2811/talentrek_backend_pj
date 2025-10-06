<?php

namespace App\Services;

class AlrajhiHostedService
{
    private $tranportalId;
    private $tranportalPassword;
    private $resourceKey;
    private $hostedUrl;

    public function __construct()
    {
        $this->tranportalId       = config('alrajhi.tranportal_id');
        $this->tranportalPassword = config('alrajhi.tranportal_password');
        $this->resourceKey        = config('alrajhi.resource_key');
        $this->hostedUrl          = config('alrajhi.hosted_url');
    }

    public function createPaymentRequest($amount, $orderId, $responseUrl, $errorUrl)
    {
        $trackId = uniqid();

        $params = [
            'id'          => $this->tranportalId,
            'password'    => $this->tranportalPassword,
            'action'      => '1', // 1 = Purchase
            'langid'      => 'EN',
            'currencycode'=> '682', // SAR
            'amt'         => $amount,
            'responseURL' => $responseUrl,
            'errorURL'    => $errorUrl,
            'trackid'     => $trackId,
            'udf1'        => $orderId,
        ];

        // Build query string
        $query = http_build_query($params);

        // Encrypt using resource key (AES 128 / ECB / PKCS5)
        $encrypted = $this->encrypt($query);

        return $this->hostedUrl . "?param=paymentInit&trandata=" . urlencode($encrypted);
    }

    private function encrypt($plainText)
    {
        $key = $this->hex2bin($this->resourceKey);
        $cipher = "AES-128-ECB";
        $encrypted = openssl_encrypt($plainText, $cipher, $key, OPENSSL_RAW_DATA);
        return bin2hex($encrypted);
    }

    private function hex2bin($hexString)
    {
        return pack("H*", $hexString);
    }

    public function decrypt($trandata)
    {
        $key = $this->hex2bin($this->resourceKey);
        $cipher = "AES-128-ECB";
        $encrypted = hex2bin($trandata);
        $decrypted = openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA);
        parse_str($decrypted, $output);
        return $output;
    }
}
