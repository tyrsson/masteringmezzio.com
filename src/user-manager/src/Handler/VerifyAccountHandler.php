<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;

class VerifyAccountHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer
    ) {
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        // Do some work...
        // Render and return a response:
        return new HtmlResponse($this->renderer->render(
            'user-manager::verify-account',
            [] // parameters to pass to template
        ));
    }
}
