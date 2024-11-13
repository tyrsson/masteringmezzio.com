<?php

declare(strict_types=1);

namespace Message\View\Helper;

use Message\SystemMessage;
use Message\SystemMessenger as Messenger;

use function sprintf;

class SystemMessenger
{
    public final const MESSAGE_KEY = SystemMessage::SYSTEM_MESSAGE_KEY;

    private const MESSAGE_DIALOG = <<<'EOD'
        <dialog id="systemMessage" open>
            <article>
                <p>%s</p>
                <footer>
                <button id="dismissSystemMessage">Close</button>
                </footer>
            </article>
        </dialog>
    EOD;

    private ?string $systemMessage;
    private Messenger $messenger;

    public function __invoke(
        string $messageKey,
        $default = null
    ) {
        $this->systemMessage = $this->messenger->getMessage(key: $messageKey, default: $default);
        if ($this->hasMessage()) {
            return sprintf(
                static::MESSAGE_DIALOG,
                $this->systemMessage
            );
        }
    }

    public function hasMessage(): bool
    {
        return $this->systemMessage !== null;
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
