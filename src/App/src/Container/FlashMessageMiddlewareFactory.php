<?php

declare(strict_types=1);

namespace App\Container;

use App\SystemMessageInterface;
use Mezzio\Flash\FlashMessageMiddleware;
use Psr\Container\ContainerInterface;

final class FlashMessageMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): FlashMessageMiddleware
    {
        return new FlashMessageMiddleware(
            attributeKey: SystemMessageInterface::class
        );
    }
}
