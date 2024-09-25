<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class EmailValidationHandlerFactory
{
    public function __invoke(ContainerInterface $container) : EmailValidationHandler
    {
        return new EmailValidationHandler($container->get(TemplateRendererInterface::class));
    }
}
