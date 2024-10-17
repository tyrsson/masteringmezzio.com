<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class CrudHandlerFactory
{
    public function __invoke(ContainerInterface $container) : CrudHandler
    {
        return new CrudHandler($container->get(TemplateRendererInterface::class));
    }
}
