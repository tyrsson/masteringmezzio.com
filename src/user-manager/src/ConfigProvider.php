<?php

declare(strict_types=1);

namespace UserManager;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Application;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Authorization\AuthorizationInterface;
use Mezzio\Authorization\AuthorizationMiddleware;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'authentication'            => $this->getAuthenticationConfig(),
            'dependencies'              => $this->getDependencies(),
            'filters'                   => $this->getFilters(),
            'form_elements'             => $this->getFormElementConfig(),
            'mezzio-authorization-rbac' => $this->getAuthorizationConfig(),
            'routes'                    => $this->getRouteConfig(),
            'templates'                 => $this->getTemplates(),
        ];
    }

    public function getAuthenticationConfig(): array
    {
        return [
            'redirect' => '/user-manager/account', // redirect for authentication component post login
            'username' => 'email',
            'password' => 'password',
        ];
    }

    public function getAuthorizationConfig(): array
    {
        return [
            'roles'       => [
                'Administrator' => [],
                //'Editor'        => ['Administrator'],
                //'Contributor'   => ['Editor'],
                'User'          => ['Administrator'],
                'Guest'         => ['User'],
            ],
            'permissions' => [
                'Guest' => [
                    'home.read',
                    'user-manager.login',
                    'user-manager.register',
                ],
                'User'  => [
                    'user-manager.logout',
                    'user-manager.account',
                ],
                'Administrator' => [
                    'admin.dashboard.read',
                ],
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                AuthenticationInterface::class => PhpSession::class,
                AuthorizationInterface::class  => Authz\Rbac::class,
                UserRepositoryInterface::class => UserRepository\TableGateway::class,
            ],
            'delegators' => [
                Application::class => [
                    ApplicationConfigInjectionDelegator::class,
                ],
            ],
            'factories'  => [
                Authz\Rbac::class                    => Authz\RbacFactory::class,
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

    public function getFilters(): array
    {
        return [
            'factories' => [
                Filter\HttpMethodToRbacPermissionFilter::class => InvokableFactory::class,
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
                'path'       => '/user-manager/login',
                'name'       => 'user-manager.login', // todo: update name to use um prefix
                'middleware' => [
                    //AuthorizationMiddleware::class,
                    BodyParamsMiddleware::class,
                    Handler\LoginHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST]
            ],
            [
                'path'       => '/user-manager/logout',
                'name'       => 'user-manager.logout',
                'middleware' => [
                    //AuthorizationMiddleware::class,
                    BodyParamsMiddleware::class,
                    Handler\LogoutHandler::class,
                ],
            ],
            [
                'path'        => '/user-manager/register',
                'name'        => 'user-manager.register',
                'middleware'  => [
                    BodyParamsMiddleware::class,
                    //AuthorizationMiddleware::class,
                    Handler\RegistrationHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST],
            ],
            [
                'path'        => '/user-manager/account',
                'name'        => 'user-manager.account',
                'middleware'  => [
                    BodyParamsMiddleware::class,
                    AuthorizationMiddleware::class,
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
