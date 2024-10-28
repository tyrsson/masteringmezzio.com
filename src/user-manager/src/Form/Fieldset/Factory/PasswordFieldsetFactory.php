<?php

declare(strict_types=1);

namespace UserManager\Form\Fieldset\Factory;

use App\ConfigProvider as AppProvider;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use UserManager\Form\Fieldset\ChangePasswordFieldset;
use UserManager\Form\Fieldset\PasswordFieldset;
use Webinertia\Validator\Password;

final class PasswordFieldsetFactory implements FactoryInterface
{
    /** @inheritDoc */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): PasswordFieldset|ChangePasswordFieldset
    {
        return new $requestedName(
            options: [
                'password_options' => $container->get('config')[AppProvider::APP_SETTINGS_KEY][Password::class]['options']
            ]
        );
    }
}
