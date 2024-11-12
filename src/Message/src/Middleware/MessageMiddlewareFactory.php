<?php

declare(strict_types=1);

namespace Message\Middleware;

use Laminas\EventManager\EventManager;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\SharedEventManager;
use Message\MessageListener;
use Message\View\Helper\SystemMessenger;
use Psr\Container\ContainerInterface;
use Laminas\View\HelperPluginManager;


final class MessageMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): MessageMiddleware
    {
        /** @var EventManager */
        $eventManager = $container->has(EventManagerInterface::class)
                        ? $container->get(EventManagerInterface::class)
                        : new EventManager(new SharedEventManager());

        $helperManager = $container->get(HelperPluginManager::class);

        return new MessageMiddleware(
            $eventManager,
            $container->get(MessageListener::class),
            $helperManager->get(SystemMessenger::class)
        );
    }
}
