<?php

declare(strict_types=1);

namespace Message\Container;

use Laminas\EventManager\EventManager;
use Laminas\EventManager\EventManagerInterface;
use Laminas\ServiceManager\Exception;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function get_debug_type;
use function gettype;
use function is_object;
use function sprintf;

/**
 * @psalm-type MessageListenerSpec = array{
 *     listener: non-empty-string,
 *     priority?: int,
 * }
 */
class MessageListenerAttachmentDelegator
{
    private const DEFAULT_PRIORITY = 1;
    /**
     * Decorate an EventManager instance by attaching its listeners from configuration.
     * @param ContainerInterface $container
     * @param string $serviceName
     * @param callable $callback
     * @return EventManagerInterface
     * @throws InvalidServiceException If $callback produces something other than EventManagerInterface instance
     * @throws NotFoundExceptionInterface If $spec['listener'] is not found in the container
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $serviceName, callable $callback): EventManagerInterface
    {
        $eventManager = $callback();
        if (! $eventManager instanceof EventManager) {
            throw new Exception\InvalidServiceException(sprintf(
                'Delegator factory %s cannot operate on a %s; please map it only to the %s service',
                self::class,
                is_object($eventManager) ? $eventManager::class . ' instance' : gettype($eventManager),
                EventManager::class
            ));
        }

        if (! $container->has('config')) {
            return $eventManager;
        }

        /**
         * The array shape is forced here as it cannot be inferred
         *
         * @psalm-var array{
         *     message_listeners?: list<MessageListenerSpec>,
         * } $config
         */
        $config = $container->get('config');
        $config['message_listeners'] ??= [];
        if ($config['message_listeners'] !== []) {
            foreach($config['message_listeners'] as $spec) {
                $listener = $container->get($spec['listener']); // will throw exception if factory is not provided
                $priority = $spec['priority'] ?? static::DEFAULT_PRIORITY;
                $listener->attach($eventManager, $priority);
            }
        }

        return $eventManager;
    }
}
