<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class VerifyAccountHandlerFactory
{
    public function __invoke(ContainerInterface $container) : VerifyAccountHandler
    {
        return new VerifyAccountHandler($container->get(TemplateRendererInterface::class));
    }
}
