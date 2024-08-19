<?php

declare(strict_types=1);

namespace App\Middleware;

use Cm\Storage\PageRepository;
use Cm\Storage\SettingsRepository;
use Psr\Container\ContainerInterface;

class ContextMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ContextMiddleware
    {
        return new ContextMiddleware(
            $container->get(PageRepository::class),
            $container->get(SettingsRepository::class)
        );
    }
}
