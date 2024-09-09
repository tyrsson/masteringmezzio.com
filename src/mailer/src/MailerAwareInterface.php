<?php

declare(strict_types=1);

namespace Mailer;

interface MailerAwareInterface
{
    public function setMailer(MailerInterface $mailerInterface): void;
    public function getMailer(): MailerInterface;
}
