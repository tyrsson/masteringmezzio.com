<?php

declare(strict_types=1);

namespace Message\Exception;

use InvalidArgumentException;
use Message\Middleware\MessageMiddleware;
use Message\SystemMessengerInterface;

use function sprintf;

class InvalidSystemMessengerImplementationException extends InvalidArgumentException implements ExceptionInterface
{
    public static function forClass(string $class): self
    {
        return new self(sprintf(
            'Cannot use "%s" within %s; does not implement %s',
            $class,
            MessageMiddleware::class,
            SystemMessengerInterface::class
        ));
    }
}
