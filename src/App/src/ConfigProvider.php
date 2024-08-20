<?php

declare(strict_types=1);

namespace App;

use Fig\Http\Message\RequestMethodInterface as Http;
use Mezzio\Authorization\AuthorizationMiddleware;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;

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
            'dependencies'       => $this->getDependencies(),
            'templates'          => $this->getTemplates(),
            'routes'             => $this->getRoutes(),
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

    public function getRoutes(): array
    {
        return [
            [
                'path' => '/',
                'name' => 'home',
                'middleware' => [
                    Handler\HomePageHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET],
            ],
            [
                'path' => '/admin',
                'name' => 'admin.dashboard',
                'middleware' => [
                    AuthorizationMiddleware::class,
                    BodyParamsMiddleware::class,
                    Handler\DashboardHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET],
            ],
            [
                'path' => '/api/ping',
                'name' => 'api.ping',
                'middleware' => [
                    Handler\PingHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET],
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
                'app'              => [__DIR__ . '/../templates/app'],
                'error'            => [__DIR__ . '/../templates/error'],
                'layout'           => [__DIR__ . '/../templates/layout'],
                'app-endpoint'     => [__DIR__ . '/../templates/api-endpoint'],
                'app-oob-partial'  => [__DIR__ . '/../templates/oob-partial'],
            ],
        ];
    }
}
