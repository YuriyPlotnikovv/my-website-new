<?php

namespace core;
require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../apiKeys.php';

use core\YandexCaptcha;

class FormHelper
{
    public const STATE_VALIDATION_ERROR = 'VALIDATION_ERROR';
    public const STATE_SAVE_ERROR = 'SAVE_ERROR';
    public const STATE_CAPTCHA_ERROR = 'CAPTCHA_ERROR';
    public const STATE_SUCCESS = 'SUCCESS';

    private ?array $data;
    private bool $useCaptcha;
    private bool $isCaptchaPassed = false;

    public function __construct(bool $useCaptcha = true)
    {
        $this->useCaptcha = $useCaptcha;
        $this->data = $this->getData();
        $this->verifyCaptcha();
    }

    private function getData(): ?array
    {
        if (!$_POST) {
            return null;
        }

        return array_change_key_case($_POST, CASE_UPPER);
    }

    private function verifyCaptcha(): void
    {
        global $captchaPublicApiKey;
        global $captchaPrivateApiKey;

        if (!$this->useCaptcha) {
            $this->isCaptchaPassed = true;
            return;
        }

        $token = $this->data['CAPTCHARESPONSE'] ?? '';
        if (empty($token)) {
            $this->isCaptchaPassed = false;
            return;
        }

        $captcha = new YandexCaptcha($captchaPublicApiKey, $captchaPrivateApiKey);
        $this->isCaptchaPassed = $captcha->verify($token);
    }

    public function help(): void
    {
        if ($this->data === null) {
            $this->respond(false, self::STATE_VALIDATION_ERROR);
            return;
        }

        if ($this->useCaptcha && !$this->isCaptchaPassed) {
            $this->respond(false, self::STATE_CAPTCHA_ERROR);
            return;
        }

        if (!$this->validateData()) {
            $this->respond(false, self::STATE_VALIDATION_ERROR);
            return;
        }

        if ($this->sendMail()) {
            $this->respond(true, self::STATE_SUCCESS);
        } else {
            $this->respond(false, self::STATE_SAVE_ERROR);
        }
    }

    private function respond(bool $success, string $code): void
    {
        $payload = [
            'success' => $success,
            'state' => $code,
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function validateData(): bool
    {
        $name = $this->data['NAME'] ?? '';
        if (empty($name)) {
            return false;
        }

        $name = trim(strip_tags($name));
        if (mb_strlen($name) < 2) {
            return false;
        }

        $email = $this->data['EMAIL'] ?? '';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $message = $this->data['MESSAGE'] ?? '';
        $message = trim(strip_tags($message));
        $len = mb_strlen($message);
        if ($len < 50 || $len > 500) {
            return false;
        }

        $agreement = $this->data['AGREEMENT'] ?? '';
        if (empty($agreement)) {
            return false;
        }

        $this->data = [
            'NAME' => $name,
            'EMAIL' => $email,
            'MESSAGE' => $message,
            'AGREEMENT' => true,
            'CAPTCHARESPONSE' => $this->data['CAPTCHARESPONSE'] ?? '',
        ];

        return true;
    }

    private function sendMail(): bool
    {
        $to = 'yuriy.plotnikovv@yandex.ru';
        $subject = 'Сообщение с формы обратной связи на сайте yuriyplotnikovv.ru';
        $headers = "From: {$this->data['EMAIL']}\r\n";
        $headers .= "Reply-To: {$this->data['EMAIL']}\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

        $body = "Имя: {$this->data['NAME']}\n";
        $body .= "E‑mail: {$this->data['EMAIL']}\n\n";
        $body .= "Сообщение:\n{$this->data['MESSAGE']}\n";

        return mail($to, $subject, $body, $headers);
    }
}
