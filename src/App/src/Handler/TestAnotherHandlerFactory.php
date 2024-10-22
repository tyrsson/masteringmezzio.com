<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class TestAnotherHandlerFactory
{
    public function __invoke(ContainerInterface $container) : TestAnotherHandler
    {
        return new TestAnotherHandler($container->get(TemplateRendererInterface::class));
    }
}
