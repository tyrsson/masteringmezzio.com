<?php

declare(strict_types=1);

namespace Message\Event;

use Laminas\EventManager\Event;
use Mezzio\Authentication\UserInterface;
use Webmozart\Assert\Assert;

class MessageEvent extends Event
{
    public final const EVENT_EMAIL_MESSAGE = 'emailMessage';

    public bool $notify = false;

    public function setTarget($target)
    {
        assert::isInstanceOf(
            $target,
            UserInterface::class,
            '$target must be an instance of: ' . UserInterface::class
        );
        $this->target = $target;
    }

    public function setNotify(bool $notify = false): void
    {
        if ($notify) {
            $this->notify = $notify;
        }
    }

    public function getNotify(): bool
    {
        return $this->notify;
    }

    public function setNotificationBody(string $body): void
    {
        $this->setNotify(true);
        $this->setParam('notificationBody', $body);
    }

    public function getNotificationBody(): ?string
    {
        return $this->getParam('notificationBody');
    }
}
