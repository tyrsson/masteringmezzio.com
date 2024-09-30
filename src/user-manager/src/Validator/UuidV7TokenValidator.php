<?php

declare(strict_types=1);

namespace UserManager\Validator;

use DateInterval;
use DateTimeImmutable;
use Laminas\Validator\Db\AbstractDb;
use Ramsey\Uuid\Rfc4122\UuidV7;

/**
 *
 * @package UserManager\Validator
 *
 * Used for validating uuidv7 expiring tokens.
 */
final class UuidV7TokenValidator extends AbstractDb
{
    /** @var array<string, string> Message templates */
    protected $messageTemplates = [
        self::ERROR_RECORD_FOUND    => 'Token has expired.',
    ];

    public function __construct($options = [])
    {
        parent::__construct($options);
    }

    public function isValid($value)
    {

    }
}
