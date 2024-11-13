<?php

declare(strict_types=1);

namespace Message\Container;

use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;

use function method_exists;

final class EventManagerAwareDelegator
{
    public function __invoke(
        ContainerInterface $container,
        string $serviceName,
        callable $callback
    ) {
        // call services __invoke method to get an instance
        $service = $callback();
        // include a duck-type for the method name provided by the interface, ie if they just used the trait
        if ($service instanceof EventManagerAwareInterface || method_exists($service, 'setEventManager')) {
            $service->setEventManager($container->get(EventManagerInterface::class));
        }

        return $service;
    }
}
