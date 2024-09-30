<?php

declare(strict_types=1);

namespace UserManager\Form\Fieldset\Factory;

use App\ConfigProvider as AppProvider;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use UserManager\Form\Fieldset\PasswordFieldset;
use Webinertia\Validator\Password;

final class PasswordFieldsetFactory implements FactoryInterface
{
    /** @param string $requestedName */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): PasswordFieldset
    {
        return new PasswordFieldset(
            $container->get('config')[AppProvider::APP_SETTINGS_KEY][Password::class]['options']
        );
    }
}
