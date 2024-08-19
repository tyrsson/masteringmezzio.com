<?php

declare(strict_types=1);

namespace Htmx\Middleware;

use Htmx\RequestHeaders as Htmx;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use const JSON_PRETTY_PRINT;

use function json_encode;

class HtmxMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TemplateRendererInterface $template,
        private array $htmxConfig
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // $response = $handler->handle($request);
        // reset the isAjax to true to support Axleus / Webinertia components
        if ($request->hasHeader(Htmx::HX_Request->value)) {
            $request = $request->withAttribute('isAjax', true);
            $request = $request->withAttribute('isHtmx', true);

            if ($request->hasHeader(Htmx::HX_Trigger->value)) {
                $request = $request->withAttribute(Htmx::HX_Trigger->value, $request->getHeader(Htmx::HX_Trigger->value)[0]);
            }

            $this->template->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'layout',
                false
            );
        }
        if ($this->htmxConfig['htmx']['enable']) {
            $this->template->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'htmxConfig',
                json_encode($this->htmxConfig['htmx']['config'])
            );
        }
        return $handler->handle($request);
    }
}
