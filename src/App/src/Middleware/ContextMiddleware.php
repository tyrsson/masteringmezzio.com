<?php

declare(strict_types=1);

namespace App\Middleware;

use Cm\Storage\PageRepository;
use Cm\Storage\SettingsRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ContextMiddleware implements MiddlewareInterface
{
    public function __construct(
        private PageRepository $pageRepo,
        private SettingsRepository $settingsRepo
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $settings = $this->settingsRepo->fetchContext();
        $request  = $request->withAttribute('settings', $settings);
        $menu     = $this->pageRepo->findMenu();
        $request  = $request->withAttribute(
            'showInMenu',
            $menu
        );
        $request  = $request->withAttribute('activeLinks', $menu);
        return $handler->handle($request);
    }
}
