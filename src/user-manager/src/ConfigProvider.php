<?php

declare(strict_types=1);

namespace UserManager;

use Fig\Http\Message\RequestMethodInterface as Http;
use Mezzio\Application;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\AuthenticationMiddleware;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'authentication' => $this->getAuthenticationConfig(),
            'dependencies'   => $this->getDependencies(),
            'form_elements'  => $this->getFormElementConfig(),
            'routes'         => $this->getRouteConfig(),
            'templates'      => $this->getTemplates(),
        ];
    }

    public function getAuthenticationConfig(): array
    {
        return [
            'redirect' => '/um/account', // redirect for authentication component post login
            'username' => 'email',
            'password' => 'password',
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                AuthenticationInterface::class => PhpSession::class,
                UserRepositoryInterface::class => UserRepository\TableGateway::class,
            ],
            'delegators' => [
                Application::class => [
                    ApplicationConfigInjectionDelegator::class,
                ],
            ],
            'factories'  => [
                Handler\AccountHandler::class        => Handler\AccountHandlerFactory::class,
                Handler\LoginHandler::class          => Handler\LoginHandlerFactory::class,
                Handler\LogoutHandler::class         => Handler\LogoutHandlerFactory::class,
                Handler\RegistrationHandler::class   => Handler\RegistrationHandlerFactory::class,
                Handler\ResetPasswordHandler::class  => Handler\ResetPasswordHandlerFactory::class,
                Handler\VerifyAccountHandler::class  => Handler\VerifyAccountHandlerFactory::class,
                Middleware\IdentityMiddleware::class => Middleware\IdentityMiddlewareFactory::class,
                UserRepository\TableGateway::class   => UserRepository\TableGatewayFactory::class,
            ],
        ];
    }

    public function getFormElementConfig(): array
    {
        return [
            'factories' => [
                Form\Login::class => Form\LoginFactory::class,
            ],
        ];
    }

    public function getRouteConfig(): array
    {
        return [
            [
                'path'       => '/um/login',
                'name'       => 'um.login', // todo: update name to use um prefix
                'middleware' => [
                    BodyParamsMiddleware::class,
                    Handler\LoginHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST]
            ],
            [
                'path'       => '/um/logout',
                'name'       => 'um.logout',
                'middleware' => [
                    BodyParamsMiddleware::class,
                    AuthenticationMiddleware::class,
                    Handler\LogoutHandler::class,
                ],
            ],
            [
                'path'        => '/um/register',
                'name'        => 'um.register',
                'middleware' => [
                    BodyParamsMiddleware::class,
                    AuthenticationMiddleware::class,
                    Handler\RegistrationHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST],
            ],
            [
                'path'        => '/um/account',
                'name'        => 'um.account',
                'middleware' => [
                    BodyParamsMiddleware::class,
                    AuthenticationMiddleware::class,
                    Handler\AccountHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST],
            ],
        ];
    }

    public function getTemplates(): array
    {
        return [
            'paths' => [
                'user-manager'             => [__DIR__ . '/../templates/user-manager'],
                'user-manager-oob-partial' => [__DIR__ . '/../templates/oob-partial'],
                'user-manager-partial'     => [__DIR__ . '/../templates/partial'],
            ],
        ];
    }
}
