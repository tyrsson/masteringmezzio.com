<?php

declare(strict_types=1);

namespace Message\View\Helper;

use Message\SystemMessage;
use Message\SystemMessenger as Messenger;

class SystemMessenger
{
    public final const MESSAGE_KEY = SystemMessage::SYSTEM_MESSAGE_KEY;

    private Messenger $messenger;

    public function __invoke(
        string $messageKey,
        $default = null
    ) {
        return $this->messenger->getMessage(
            key: $messageKey,
            default: $default
        );
    }

    public function setMessenger(Messenger $messenger): void
    {
        $this->messenger = $messenger;
    }

    public function getMessenger(): ?Messenger
    {
        return $this->messenger;
    }
}
