<?php

declare(strict_types=1);

namespace Htmx\Middleware;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class HtmxMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : HtmxMiddleware
    {
        return new HtmxMiddleware(
            $container->get(TemplateRendererInterface::class),
            $container->get('config')['htmx_config']
        );
    }
}
