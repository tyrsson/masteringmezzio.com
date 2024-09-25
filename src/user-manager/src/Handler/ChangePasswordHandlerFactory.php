<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ChangePasswordHandlerFactory
{
    public function __invoke(ContainerInterface $container) : ChangePasswordHandler
    {
        return new ChangePasswordHandler($container->get(TemplateRendererInterface::class));
    }
}
