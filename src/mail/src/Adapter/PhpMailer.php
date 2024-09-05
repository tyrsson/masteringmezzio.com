<?php

declare(strict_types=1);

namespace Mail\Adapter;

use Mail\MailerInterface;
use PHPMailer\PHPMailer\PHPMailer as BaseMailer;

final class PhpMailer implements MailerInterface
{
    public function __construct(
        private BaseMailer $mailer
    ) {

    }

    public function setSubject(string $subject): void
    {
        $this->mailer->Subject = $subject;
    }

    public function getSubject(): ?string
    {
        return $this->mailer->Subject;
    }

    public function setBody(string $body): void
    {
        $this->mailer->Body = $body;
    }

    public function getBody(): ?string
    {
        return $this->mailer->Body;
    }

    public function __call($name, $arguments)
    {
        return $this->mailer->$name(...$arguments);
    }
}
