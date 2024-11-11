<?php

declare(strict_types=1);

namespace UserManager\User\Event;

use Message\Event\MessageEvent;

final class VerificationEmail extends MessageEvent
{
    public const EVENT_VERIFY_ACCOUNT_EMAIL = 'verifyAccountEmail';
}
