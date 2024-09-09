<?php

declare(strict_types=1);

namespace Mailer;

/**
 * The configuration provider for the mail module
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
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            static::class  => $this->getAdapterConfig(),
        ];
    }

    public function getAdapterConfig(): array
    {
        return [
            Adapter\AdapterInterface::class => [],
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'aliases' => [
                Adapter\AdapterInterface::class => Adapter\PhpMailer::class, // required mapping
                MailerInterface::class          => Mailer::class,
            ],
            'factories'  => [
                Adapter\PhpMailer::class           => Adapter\PhpMailerFactory::class,
                Mailer::class                      => MailerFactory::class,
                Middleware\MailerMiddleware::class => Middleware\MailerMiddlewareFactory::class
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'mail'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
