<?php

declare(strict_types=1);

namespace Mail;

use Mail\MailerInterface;
use Mail\MailerAwareInterface;
use Psr\Container\ContainerInterface;

final class MailerAwareDelegator
{
    public function __invoke(ContainerInterface $container, string $serviceName, callable $callback)
    {
        $service = $callback();
        if (! $service instanceof MailerAwareInterface) {
            return $service;
        }
        $service->setMailer($container->get(MailerInterface::class));
        return $service;
    }
}
