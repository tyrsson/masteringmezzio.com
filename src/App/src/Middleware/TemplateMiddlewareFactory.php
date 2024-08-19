<?php

declare(strict_types=1);

namespace App\Middleware;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class TemplateMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): TemplateMiddleware
    {
        return new TemplateMiddleware(
            $container->get(TemplateRendererInterface::class),
            $container->get('config')
        );
    }
}
