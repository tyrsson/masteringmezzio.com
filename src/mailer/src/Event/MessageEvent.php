<?php

declare(strict_types=1);

namespace Mailer\Event;

use Laminas\EventManager\Event;
use Mezzio\Authentication\UserInterface;
use Webmozart\Assert\Assert;

class MessageEvent extends Event
{
    public final const EVENT_EMAIL_MESSAGE = 'emailMessage';

    public function setTarget($target)
    {
        assert::isInstanceOf(
            $target,
            UserInterface::class,
            '$target must be an instance of: ' . UserInterface::class
        );
        $this->target = $target;
    }
}
