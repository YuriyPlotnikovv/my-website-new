<?php
namespace core;
require_once __DIR__ . '/../apiKeys.php';

class YandexCaptcha
{
    private const string PUBLIC_KEY = '';
    private const string SECRET_KEY = '';
    private const string STATUS_OK = 'ok';

    private string $url = 'https://smartcaptcha.yandexcloud.net/validate';

    public function getPublicKey(): string
    {
        return self::PUBLIC_KEY;
    }

    public function verify($response): bool
    {
        $captchaParams = [
            'secret' => self::SECRET_KEY,
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