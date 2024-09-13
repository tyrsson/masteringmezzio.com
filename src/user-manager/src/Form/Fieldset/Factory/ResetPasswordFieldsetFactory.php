<?php

declare(strict_types=1);

namespace UserManager\Form\Fieldset\Factory;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;
use UserManager\Form\Fieldset\ResetPasswordFieldset;
use UserManager\ConfigProvider;

final class ResetPasswordFieldsetFactory
{
    public function __invoke(ContainerInterface $container): ResetPasswordFieldset
    {

    }
}
