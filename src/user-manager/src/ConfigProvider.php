<?php

declare(strict_types=1);

namespace UserManager;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mailer\ConfigProvider as MailConfigProvider;
use Mailer\Adapter\AdapterInterface;
use Mailer\Middleware\MailerMiddleware;
use Mezzio\Application;
use Mezzio\Container\ApplicationConfigInjectionDelegator;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Authorization\AuthorizationInterface;
use Mezzio\Authorization\AuthorizationMiddleware;
use Mezzio\Authorization\Rbac\LaminasRbacAssertionInterface;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use Webinertia\Validator\Password;

final class ConfigProvider
{
    public final const MAIL_MESSAGE_TEMPLATES   = 'message_templates';
    public final const MAIL_VERIFY_MESSAGE_BODY = 'verify_message_body';
    public final const MAIL_VERIFY_SUBJECT      = 'verify_message_subject';
    public const USERMANAGER_TABLE_NAME         = 'user-manager_table_name';
    public const APPEND_HTTP_METHOD_TO_PERMS    = 'append_http_method_to_permissions';
    public const APPEND_ONLY_MAPPED             = 'append_only_mapped';
    public const RBAC_MAPPED_ROUTES             = 'rbac_mapped_routes';

    public function __invoke(): array
    {
        return [
            'app_settings'              => $this->getAppSettings(),
            'authentication'            => $this->getAuthenticationConfig(),
            'dependencies'              => $this->getDependencies(),
            'filters'                   => $this->getFilters(),
            'form_elements'             => $this->getFormElementConfig(),
            'input_filters'             => $this->getInputFilterConfig(),
            'mezzio-authorization-rbac' => $this->getAuthorizationConfig(),
            'routes'                    => $this->getRouteConfig(),
            'templates'                 => $this->getTemplates(),
            'view_helpers'              => $this->getViewHelpers(),
            static::class               => [
                static::USERMANAGER_TABLE_NAME      => 'users',
                static::APPEND_HTTP_METHOD_TO_PERMS => true, // bool true|false
                static::APPEND_ONLY_MAPPED          => true, // bool true|false
                static::RBAC_MAPPED_ROUTES          => $this->getRbacMappedRoutes(), // array of routes to map http methods to
            ],
            MailConfigProvider::class => $this->getMailConfig(),
        ];
    }

    public function getAppSettings(): array
    {
        return [
            'account_verification_token_expire_time' => '1 Hour',
            Password::class => [
                'options' => [
                    'length'  => 8, // overall length of password
                    'upper'   => 1, // uppercase count
                    'lower'   => 2, // lowercase count
                    'digit'   => 2, // digit count
                    'special' => 2, // special char count
                ],
            ],
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
                    'Home',
                    'Login',
                    'Register',
                    'Reset Password',
                    'Verify Account',
                ],
                'User'  => [
                    'Logout',
                    'Account.read',
                ],
                'Administrator' => [
                ],
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases'    => [
                AuthenticationInterface::class       => PhpSession::class,
                AuthorizationInterface::class        => Authz\Rbac::class,
                LaminasRbacAssertionInterface::class => Authz\UserAssertion::class,
                UserRepositoryInterface::class       => UserRepository\TableGateway::class,
            ],
            'delegators' => [
                Application::class => [
                    ApplicationConfigInjectionDelegator::class,
                ],
            ],
            'factories'  => [
                Authz\Rbac::class                    => Authz\RbacFactory::class,
                Authz\UserAssertion::class           => InvokableFactory::class,
                Handler\AccountHandler::class        => Handler\AccountHandlerFactory::class,
                Handler\ChangePasswordHandler::class => Handler\ChangePasswordHandlerFactory::class,
                Handler\LoginHandler::class          => Handler\LoginHandlerFactory::class,
                Handler\LogoutHandler::class         => Handler\LogoutHandlerFactory::class,
                Handler\RegistrationHandler::class   => Handler\RegistrationHandlerFactory::class,
                Handler\ResetPasswordHandler::class  => Handler\ResetPasswordHandlerFactory::class,
                Handler\VerifyAccountHandler::class  => Handler\VerifyAccountHandlerFactory::class,
                Helper\VerificationHelper::class     => Helper\VerificationHelperFactory::class,
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
                Form\Fieldset\AcctDataFieldset::class      => Form\Fieldset\Factory\AcctDataFieldsetFactory::class,
                Form\Fieldset\ResendVerification::class    => InvokableFactory::class,
                Form\Login::class                          => Form\LoginFactory::class,
                Form\Register::class                       => Form\RegisterFactory::class,
                Form\ResendVerification::class             => Form\ResendVerificationFactory::class,
                Form\ResetPassword::class                  => Form\ResetPasswordFactory::class,
                Form\Fieldset\ResetPasswordFieldset::class => Form\Fieldset\Factory\ResetPasswordFieldsetFactory::class,
            ],
        ];
    }

    public function getInputFilterConfig(): array
    {
        return [
            'factories' => [
                InputFilter\AcctDataFilter::class => InputFilter\AcctDataFilterFactory::class,
            ],
        ];
    }

    public function getMailConfig(): array
    {
        return [
            AdapterInterface::class => [
                static::MAIL_MESSAGE_TEMPLATES => [
                    static::MAIL_VERIFY_SUBJECT      => '%s Account Verification.',
                    static::MAIL_VERIFY_MESSAGE_BODY => 'Please click the link to verify your email address <a href="%s%s">Click Here!!</a>',
                ],
            ],
        ];
    }

    public function getRbacMappedRoutes(): array
    {
        return [
            'Account',
        ];
    }

    public function getRouteConfig(): array
    {
        return [
            [
                'path'       => '/user-manager/login',
                'name'       => 'Login', // todo: update name to use um prefix
                'middleware' => [
                    //AuthorizationMiddleware::class,
                    BodyParamsMiddleware::class,
                    Handler\LoginHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST]
            ],
            [
                'path'       => '/user-manager/logout',
                'name'       => 'Logout',
                'middleware' => [
                    //AuthorizationMiddleware::class,
                    BodyParamsMiddleware::class,
                    Handler\LogoutHandler::class,
                ],
            ],
            [
                'path'        => '/user-manager/register',
                'name'        => 'Register',
                'middleware'  => [
                    BodyParamsMiddleware::class,
                    MailerMiddleware::class,
                    Handler\RegistrationHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST],
            ],
            [
                'path'        => '/user-manager/reset[/{id:\d+}[/{token:[a-zA-Z0-9-]+}]]',
                'name'        => 'Reset Password',
                'middleware'  => [
                    BodyParamsMiddleware::class,
                    MailerMiddleware::class,
                    Handler\ResetPasswordHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST],
            ],
            [
                'path'        => '/user-manager/verify[/{id:\d+}[/{token:[a-zA-Z0-9-]+}]]',
                'name'        => 'Verify Account',
                'middleware'  => [
                    BodyParamsMiddleware::class,
                    MailerMiddleware::class,
                    Handler\VerifyAccountHandler::class,
                ],
                'allowed_methods' => [Http::METHOD_GET, Http::METHOD_POST],
            ],
            [
                'path'        => '/user-manager/account',
                'name'        => 'Account',
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

    public function getViewHelpers(): array
    {
        return [
            'aliases'   => [
                'authz'         => View\Helper\RbacHelper::class,
                'rbac'          => View\Helper\RbacHelper::class,
                'authorization' => View\Helper\RbacHelper::class,
            ],
            'factories' => [
                View\Helper\RbacHelper::class => View\Helper\RbacHelperFactory::class,
            ],
        ];
    }
}
