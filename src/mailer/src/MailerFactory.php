<?php

declare(strict_types=1);

namespace Mailer;

use Psr\Container\ContainerInterface;

final class MailerFactory
{
    public function __invoke(ContainerInterface $container): Mailer
    {
        return new Mailer($container->get(Adapter\AdapterInterface::class));
    }
}
