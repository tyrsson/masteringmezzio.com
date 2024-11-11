<?php

declare(strict_types=1);

namespace UserManager\User\Event;

use Message\Event\MessageEvent;

final class PersonalMessage extends MessageEvent
{
    public const EVENT_PERSONAL_MESSAGE = 'personalMessage';
}
