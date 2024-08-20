<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Htmx\RequestHeaders as HtmxHeader;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AccountHandler implements RequestHandlerInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Render and return a response:
        return new HtmlResponse($this->renderer->render(
            'user-manager::account',
            [
                'swapNav' => $request->getAttribute(HtmxHeader::HX_Trigger->value, false) === 'login-form',
            ]
        ));
    }
}
