<?php

declare(strict_types=1);

namespace App\Handler;

use Chubbyphp\Container\MinimalContainer;
use DI\Container as PHPDIContainer;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Model\ModelInterface;
use Mezzio\LaminasView\LaminasViewRenderer;
use Mezzio\Plates\PlatesRenderer;
use Mezzio\Router\FastRouteRouter;
use Mezzio\Router\LaminasRouter;
use Mezzio\Router\RouterInterface;
use Mezzio\Template\TemplateRendererInterface;
use Mezzio\Twig\TwigRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HomePageHandler implements RequestHandlerInterface
{
    public function __construct(
        private string $containerName,
        private RouterInterface $router,
        private ?TemplateRendererInterface $template = null
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = [];

        $model = $request->getAttribute(ModelInterface::class);
        $model->setVariables($data);
        return new HtmlResponse($this->template->render('app::home-page', $model));
    }
}
