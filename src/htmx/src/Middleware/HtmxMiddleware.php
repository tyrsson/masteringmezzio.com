<?php

declare(strict_types=1);

namespace Htmx\Middleware;

use Htmx\RequestHeaders as Htmx;
use Htmx\View\Model\FooterModel;
use Htmx\View\Model\HeaderModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\ModelInterface;
use Mezzio\Router\RouteResult;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function count;
use function explode;
use function json_encode;
use function str_contains;
use function ucfirst;

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

        if ($this->htmxConfig['enable']) {
            $this->template->addDefaultParam(
                TemplateRendererInterface::TEMPLATE_ALL,
                'htmxConfig',
                json_encode($this->htmxConfig['config'])
            );
        }

        /** @var RouteResult */
        $routeResult = $request->getAttribute(RouteResult::class);
        if ($routeResult->isSuccess()) {
            $routeName = $routeResult->getMatchedRouteName() ?? '';
        }

        // setup the Htmx ViewModel
        $model = new ViewModel();
        $model->addChild(
            new HeaderModel(
                [
                    'request' => $request,
                    'appName' => $this->htmxConfig['app_name'],
                    'title'   => $routeName,
                ]
            )
        );

        $model->addChild(new FooterModel());
        $request = $request->withAttribute(ModelInterface::class, $model);
        return $handler->handle($request);
    }
}
