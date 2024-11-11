<?php

declare(strict_types=1);

namespace Message;

use Laminas\EventManager;

/**
 * The configuration provider for the Message module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies'      => $this->getDependencies(),
            'message_listeners' => $this->getMessageListeners(),
            'templates'         => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'aliases'    => [
                EventManager\EventManagerInterface::class       => EventManager\EventManager::class,
                'EventManager'                                  => EventManager\EventManager::class,
                EventManager\SharedEventManagerInterface::class => EventManager\SharedEventManager::class,
                'SharedEventManager'                            => EventManager\SharedEventManager::class,
                SystemMessengerInterface::class                 => SystemMessenger::class,
            ],
            'delegators' => [
                EventManager\EventManager::class => [
                    Container\MessageListenerAttachmentDelegator::class,
                ],
            ],
            'invokables' => [
            ],
            'factories'  => [
                EventManager\EventManager::class         => Container\EventManagerFactory::class,
                EventManager\SharedEventManager::class   => static fn() => new EventManager\SharedEventManager(),
                MessageListener::class                   => MessageListenerFactory::class,
                Middleware\MessageMiddleware::class      => Middleware\MessageMiddlewareFactory::class,
            ],
            'initializers' => [
                Container\EventManagerInitializer::class,
            ],
        ];
    }

    public function getMessageListeners(): array
    {
        return [
            // [
            //     'listener' => MessageListener::class,
            //     //'priority' => 1,
            // ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'message'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
