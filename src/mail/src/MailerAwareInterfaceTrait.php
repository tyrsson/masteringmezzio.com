<?php

declare(strict_types=1);

namespace Mail;

trait MailerAwareInterfaceTrait
{
    protected MailerInterface $mailerInterface;

    public function setMailer(MailerInterface $mailerInterface): void
    {
        $this->mailerInterface = $mailerInterface;
    }

    public function getMailer(): MailerInterface
    {
        return $this->mailerInterface;
    }
}
