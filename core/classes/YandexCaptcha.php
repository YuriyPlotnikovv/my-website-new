<?php

namespace core;

class YandexCaptcha
{
    private const string STATUS_OK = 'ok';
    private string $publicKey;
    private string $secretKey;
    private string $url = 'https://smartcaptcha.yandexcloud.net/validate';

    public function __construct(string $publicKey, string $secretKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function verify($response): bool
    {
        $captchaParams = [
            'secret' => $this->secretKey,
            'token' => $response,
            'ip' => $_SERVER['REMOTE_ADDR'],
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($captchaParams));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === false) {
            return false;
        }

        return json_decode($result)->status === self::STATUS_OK;
    }
}