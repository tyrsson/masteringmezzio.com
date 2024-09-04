<?php

declare(strict_types=1);

namespace Mail;

interface MailerAwareInterface
{
    public function setMailer(MailerInterface $mailerInterface): void;
    public function getMailer(): MailerInterface;
}
