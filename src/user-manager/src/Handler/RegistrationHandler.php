<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UserManager\Form\Register;
use UserManager\UserRepository\TableGateway;

class RegistrationHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private UserRepositoryInterface&TableGateway $userRepositoryInterface,
        private Register $form
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Do some work...
        // Render and return a response:
        return match ($request->getMethod()) {
            Http::METHOD_GET  => $this->handleGet($request),
            Http::METHOD_POST => $this->handlePost($request),
            default => new EmptyResponse(405),
        };
    }

    private function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->renderer->render(
            'user-manager::registration',
            ['form' => $this->form] // parameters to pass to template
        ));
    }

    private function handlePost(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $this->form->setData($body);
        if ($this->form->isValid()) {
            $userEntity = $this->form->getData();
            $userEntity->offsetUnset('conf_password');
            $result = $this->userRepositoryInterface->save($userEntity, 'id');
        }

        return new HtmlResponse($this->renderer->render(
            'user-manager::registration',
            ['form' => $this->form] // parameters to pass to template
        ));
    }
}
