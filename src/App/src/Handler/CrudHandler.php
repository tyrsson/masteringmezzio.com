<?php

declare(strict_types=1);

namespace App\Handler;

use App\HandlerTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\View\Model\ModelInterface;
use Mezzio\Template\TemplateRendererInterface;

class CrudHandler implements RequestHandlerInterface
{
    use HandlerTrait;

    public function __construct(
        private TemplateRendererInterface $renderer
    ) {
    }

    public function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        $data = [
            'title' => 'crud',
        ];
        $model = $request->getAttribute(ModelInterface::class);
        $model->setVariables($data);
        return new HtmlResponse($this->renderer->render(
            'app::crud',
            $model
        ));
    }

    public function handlePost(ServerRequestInterface $request): ResponseInterface
    {
        // Create and return a response
    }

    public function handlePut(ServerRequestInterface $request): ResponseInterface
    {
        // Create and return a response
    }

    public function handleDelete(ServerRequestInterface $request): ResponseInterface
    {
        // Create and return a response
    }
}
