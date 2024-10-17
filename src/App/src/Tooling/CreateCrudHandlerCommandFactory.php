<?php

declare(strict_types=1);

namespace App\Tooling;

use Psr\Container\ContainerInterface;

use function getcwd;
use function realpath;

final class CreateCrudHandlerCommandFactory
{
    public function __invoke(ContainerInterface $container): CreateCrudHandlerCommand
    {
        return new CreateCrudHandlerCommand($container, realpath(getcwd()));
    }
}
