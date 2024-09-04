<?php

declare(strict_types=1);

namespace Mail\Adapter;

use Mail\MailerInterface;
use PHPMailer\PHPMailer\PHPMailer as BaseMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

final class PhpMailer implements MailerInterface
{
    public function __construct(
        private BaseMailer $mailer
    ) {

    }

    public function __call($name, $arguments)
    {
        return $this->mailer->$name(...$arguments);
    }
}
