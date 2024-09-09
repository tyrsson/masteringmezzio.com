<?php

declare(strict_types=1);

namespace Mailer;

interface MailerInterface
{
    public function setAdapter(Adapter\AdapterInterface $adapter): self;
    public function getAdapter(): ?Adapter\AdapterInterface;
    public function send(Adapter\AdapterInterface $adapter);
}
