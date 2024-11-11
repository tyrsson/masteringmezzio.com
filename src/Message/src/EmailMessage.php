<?php

declare(strict_types=1);

namespace Message;

class EmailMessage extends SystemMessage implements EmailMessageCapableInterface
{
    public function setMessage(string $message): void
    {
        $this->setNotify(true);
        $this->setParam('message', $message);
    }

    public function getMessage(): ?string
    {
        return $this->getParam('message');
    }
}
