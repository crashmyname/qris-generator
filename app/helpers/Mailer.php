<?php
namespace Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    protected PHPMailer $mail;
    protected ?string $lastError = null;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        // Konfigurasi SMTP dari .env
        $this->mail->isSMTP();
        $this->mail->Host       = env('SMTP_HOST');
        $this->mail->SMTPAuth   = env('SMTP_AUTH', true);
        $this->mail->Username   = env('SMTP_EMAIL');
        $this->mail->Password   = env('SMTP_PASSWORD');
        $this->mail->SMTPSecure = env('SMTP_SECURE', 'tls');
        $this->mail->Port       = env('SMTP_PORT', 587);

        // Default sender
        $this->mail->setFrom(env('SMTP_EMAIL'), env('APP_NAME', 'Mailer'));
        $this->mail->isHTML(true);
    }

    public static function make(): self
    {
        return new self();
    }

    public function from(string $email, string $name = ''): self
    {
        $this->mail->setFrom($email, $name);
        return $this;
    }

    public function to(string $email, string $name = ''): self
    {
        $this->mail->addAddress($email, $name);
        return $this;
    }

    public function cc(string $email, string $name = ''): self
    {
        $this->mail->addCC($email, $name);
        return $this;
    }

    public function bcc(string $email, string $name = ''): self
    {
        $this->mail->addBCC($email, $name);
        return $this;
    }

    public function replyTo(string $email, string $name = ''): self
    {
        $this->mail->addReplyTo($email, $name);
        return $this;
    }

    public function multipleTo(array $emails): self
    {
        foreach ($emails as $email => $name) {
            if (is_int($email)) {
                $this->to($name);
            } else {
                $this->to($email, $name);
            }
        }
        return $this;
    }

    public function multipleCC(array $emails): self
    {
        foreach ($emails as $email => $name) {
            if (is_int($email)) {
                $this->cc($name);
            } else {
                $this->cc($email, $name);
            }
        }
        return $this;
    }

    public function multipleBCC(array $emails): self
    {
        foreach ($emails as $email => $name) {
            if (is_int($email)) {
                $this->bcc($name);
            } else {
                $this->bcc($email, $name);
            }
        }
        return $this;
    }

    public function subject(string $subject): self
    {
        $this->mail->Subject = $subject;
        return $this;
    }

    public function body(string $body): self
    {
        $this->mail->Body = $body;
        return $this;
    }

    public function altBody(string $text): self
    {
        $this->mail->AltBody = $text;
        return $this;
    }

    public function addAttachment(string $filePath, string $name = ''): self
    {
        $this->mail->addAttachment($filePath, $name);
        return $this;
    }

    public function customHeader(string $name, string $value): self
    {
        $this->mail->addCustomHeader($name, $value);
        return $this;
    }

    public function send(): bool
    {
        try {
            return $this->mail->send();
        } catch (Exception $e) {
            $this->lastError = $this->mail->ErrorInfo;
            error_log("Mailer error: " . $this->lastError);
            return false;
        }
    }

    public function getError(): ?string
    {
        return $this->lastError;
    }
}
