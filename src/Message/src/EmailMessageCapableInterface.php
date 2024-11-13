<?php

declare(strict_types=1);

namespace Message;

interface EmailMessageCapableInterface
{
    /** The published event for this Message type */
    public final const EVENT_EMAIL_MESSAGE = 'emailMessage';
    /** Generic success message, concrete classes should override this value */
    public const SYSTEM_MESSAGE = <<<'EOM'
        Email sent.
    EOM;
}
