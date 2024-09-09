<?php

declare(strict_types=1);

namespace Mailer;

trait MailerAwareInterfaceTrait
{
    protected MailerInterface&Mailer $mailerInterface;

    public function setMailer(MailerInterface&Mailer $mailerInterface): void
    {
        $this->mailerInterface = $mailerInterface;
    }

    public function getMailer(): MailerInterface
    {
        return $this->mailerInterface;
    }
}
