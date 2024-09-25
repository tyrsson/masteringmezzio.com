<?php

declare(strict_types=1);

namespace Htmx\Middleware;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class HtmxMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : HtmxMiddleware
    {
        $config = $container->get('config');
        return new HtmxMiddleware(
            $container->get(TemplateRendererInterface::class),
            $config['htmx_config']
        );
    }
}
