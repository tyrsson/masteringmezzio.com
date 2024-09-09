<?php

declare(strict_types=1);

namespace Mailer;

use Mailer\MailerInterface;

final class Mailer implements MailerInterface
{
    public function __construct(
        private ?Adapter\AdapterInterface $adapter
    ) {
    }

    public function setAdapter(Adapter\AdapterInterface $adapter): self
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function getAdapter(): ?Adapter\AdapterInterface
    {
        return $this->adapter;
    }

    public function send(Adapter\AdapterInterface $adapter)
    {
        return $this->adapter->send();
    }
}
