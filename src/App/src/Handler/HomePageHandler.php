<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\View\Model\ModelInterface;
use Mezzio\Router\RouterInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
        $data  = [];
        $model = $request->getAttribute(ModelInterface::class);
        $model->setVariables($data);
        return new HtmlResponse(
            $this->template->render('app::home-page', $model)
        );
    }
}
