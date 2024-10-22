<?php

declare(strict_types=1);

namespace App\Tooling;

use Mezzio\Application;
use Mezzio\Container\ApplicationConfigInjectionDelegator as Delegator;
use Psr\Container\ContainerInterface;

trait ConfigInjectionDelegatorDetectorTrait
{
    private function delegatorIsRegistered(ContainerInterface $container): bool
    {
        $config = $container->get('config')['dependencies']['delegators'];
        if (! isset($config[Application::class])) {
            return false;
        }

        return array_key_exists(Delegator::class, array_flip($config[Application::class]));
    }
}
