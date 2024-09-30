<?php

declare(strict_types=1);

namespace Mailer\Adapter;

use PHPMailer\PHPMailer\PHPMailer as BaseMailer;

final class PhpMailer implements AdapterInterface
{
    public function __construct(
        private BaseMailer $mailer
    ) {
    }

    public function to(string $email, string $name = '', bool $clearPrevious = false): bool
    {
        if ($clearPrevious) {
            $this->mailer->clearAddresses();
        }

        return $this->mailer->addAddress($email, $name);
    }

    public function from(string $email, $name = '', $auto = true): bool
    {
        return $this->mailer->setFrom($email, $name, $auto);
    }

    public function body(string $body): void
    {
        $this->mailer->Body = $body;
    }

    public function altBody(string $altBody): void
    {
        $this->mailer->AltBody = $altBody;
    }

    public function subject(string $subject): void
    {
        $this->mailer->Subject = $subject;
    }

    public function cc(string $email, string $name = ''): bool
    {
        return $this->mailer->addCC($email, $name);
    }

    public function bcc(string $email, string $name = ''): bool
    {
        return $this->mailer->addBCC($email, $name);
    }

    public function isHtml(bool $flag = true): void
    {
        $this->mailer->isHTML($flag);
    }

    public function isSmtp(bool $flag = true): void
    {
        if ($flag) {
            $this->mailer->isSMTP();
        }
    }

    public function getSubject(): ?string
    {
        return $this->mailer->Subject;
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
