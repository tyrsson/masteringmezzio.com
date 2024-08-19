<?php

declare(strict_types=1);

namespace App;

use Laminas\Filter;
use Laminas\Validator;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserRepositoryInterface;

/**
 * The configuration provider for the App module
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
            'authentication' => ['redirect' => '/admin'],
            'dependencies'       => $this->getDependencies(),
            'templates'          => $this->getTemplates(),
            // 'view_helper_config' => $this->getViewHelperConfig(),
            // 'view_manager'       => $this->getViewManagerConfig(), // framework factory is outdated
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handler\DashboardHandler::class         => Handler\DashboardHandlerFactory::class,
                Handler\HomePageHandler::class          => Handler\HomePageHandlerFactory::class,
                Middleware\AjaxRequestMiddleware::class => Middleware\AjaxRequestMiddlewareFactory::class,
                Middleware\TemplateMiddleware::class    => Middleware\TemplateMiddlewareFactory::class,
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
                'app'          => [__DIR__ . '/../templates/app'],
                'error'        => [__DIR__ . '/../templates/error'],
                'layout'       => [__DIR__ . '/../templates/layout'],
                'app-endpoint' => [__DIR__ . '/../templates/api-endpoint'],
                'app-oob-partial'  => [__DIR__ . '/../templates/oob-partial'],
            ],
        ];
    }

    public function getViewHelperConfig(): array
    {
        return [
            'doctype' => 'HTML5',
            'body_class' => 'layout-fixed sidebar-expand-lg bg-body-tertiary',
        ];
    }

    public function getViewManagerConfig(): array
    {
        return [
            'base_path' => '/',
        ];
    }
}
