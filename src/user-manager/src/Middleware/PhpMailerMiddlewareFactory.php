<?php

declare(strict_types=1);

namespace UserManager\Middleware;

use Mail\MailerInterface;
use Psr\Container\ContainerInterface;

class PhpMailerMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): PhpMailerMiddleware
    {
        return new PhpMailerMiddleware(
            $container->get(MailerInterface::class),
            $container->get('config')
        );
    }
}
