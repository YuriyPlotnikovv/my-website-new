<?php

namespace core;
require_once __DIR__ . '/../bootstrap.php';

use core\Env;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class FormHelper
{
    public const STATE_VALIDATION_ERROR = 'VALIDATION_ERROR';
    public const STATE_SAVE_ERROR = 'SAVE_ERROR';
    public const STATE_CAPTCHA_ERROR = 'CAPTCHA_ERROR';
    public const STATE_SUCCESS = 'SUCCESS';

    private ?array $data;
    private bool $isCaptchaPassed = false;

    public function __construct()
    {
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
        $token = $this->data['CAPTCHARESPONSE'] ?? '';
        if (empty($token)) {
            $this->isCaptchaPassed = false;
            return;
        }

        $captcha = new YandexCaptcha(Env::get('CAPTCHA_PUBLIC_API_KEY'), Env::get('CAPTCHA_PRIVATE_API_KEY'));
        $this->isCaptchaPassed = $captcha->verify($token);
    }

    public function help(): void
    {
        if ($this->data === null) {
            $this->respond(false, self::STATE_VALIDATION_ERROR);
            return;
        }

        if (!$this->isCaptchaPassed) {
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
        if (mb_strlen($name) < 3) {
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
        if ($len < 10 || $len > 500) {
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
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = Env::get('SMTP_HOST', 'smtp.beget.com');
            $mail->SMTPAuth = true;
            $mail->Username = Env::get('SMTP_USER', 'noreply@yuriyplotnikovv.ru');
            $mail->Password = Env::get('SMTP_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = (int) Env::get('SMTP_PORT', '465');
            $mail->Timeout = 10;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            $fromEmail = Env::get('MAIL_FROM_EMAIL', 'noreply@yuriyplotnikovv.ru');
            $mail->setFrom($fromEmail, Env::get('MAIL_FROM_NAME', 'Yuriyplotnikovv.ru'));

            $userEmail = filter_var(trim($this->data['EMAIL']), FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE);
            $mail->addReplyTo($userEmail ?: $fromEmail);

            $mail->addAddress(Env::get('MAIL_TO', 'yuriy.plotnikovv@yandex.ru'));

            $mail->Subject = 'Сообщение с формы обратной связи';
            $mail->isHTML();
            $mail->AltBody = $this->buildTextBody();
            $mail->Body = $this->buildHtmlBody();

            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            Tools::logError('PHPMailer: ' . $e->errorMessage());
            return false;
        } catch (\Throwable $e) {
            Tools::logError('Mail unexpected error: ' . $e->getMessage());
            return false;
        }
    }

    private function buildTextBody(): string
    {
        $body = "Сообщение с формы обратной связи на сайте yuriyplotnikovv.ru:\n\n";
        $body .= "Имя: {$this->data['NAME']}\n";
        $body .= "E‑mail: {$this->data['EMAIL']}\n\n";
        $body .= "Сообщение:\n{$this->data['MESSAGE']}\n";

        return $body;
    }

    private function buildHtmlBody(): string
    {
        $name = htmlspecialchars($this->data['NAME'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($this->data['EMAIL'], ENT_QUOTES, 'UTF-8');
        $message = nl2br(htmlspecialchars($this->data['MESSAGE'], ENT_QUOTES, 'UTF-8'));

        $html = "<!DOCTYPE html>\r\n<html lang=\"ru\">\r\n";
        $html .= "<body style=\"margin:0;padding:24px;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.5;color:#333333;\">\r\n";
        $html .= "<div style=\"max-width:560px;margin:0 auto;background:#ffffff;border-radius:8px;padding:24px;\">\r\n";
        $html .= "<p style=\"margin:0 0 16px;\">Сообщение с формы обратной связи на сайте yuriyplotnikovv.ru:</p>\r\n";
        $html .= "<p style=\"margin:0 0 16px;\"><strong>Имя:</strong> {$name}<br><strong>E‑mail:</strong> {$email}</p>\r\n";
        $html .= "<p style=\"margin:0 0 8px;\"><strong>Сообщение:</strong></p>\r\n";
        $html .= "<p style=\"margin:0;\">{$message}</p>\r\n";
        $html .= "</div>\r\n</body>\r\n</html>";

        return $html;
    }
}
