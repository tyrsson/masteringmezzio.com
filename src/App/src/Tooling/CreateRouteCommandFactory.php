<?php

declare(strict_types=1);

namespace App\Tooling;

use Psr\Container\ContainerInterface;

use function getcwd;
use function realpath;

final class CreateRouteCommandFactory
{
    public function __invoke(ContainerInterface $container): CreateRouteCommand
    {
        $config = $container->get('config');

        return new CreateRouteCommand(
            realpath(getcwd()),
            isset($config['mezzio-authorization-rbac']) ? $config['mezzio-authorization-rbac'] : null
        );
    }
}
