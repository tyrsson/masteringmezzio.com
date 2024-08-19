<?php

declare(strict_types=1);

namespace UserManager\Handler;

use UserManager\Form\Login;
use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Uri;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\SessionInterface;
use Mezzio\Session\LazySession;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function in_array;

class LoginHandler implements RequestHandlerInterface
{
    private const REDIRECT_ATTRIBUTE = 'authentication:redirect';

    public function __construct(
        private TemplateRendererInterface $renderer,
        private PhpSession $adapter,
        private Login $form,
        private $config
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // if (! $request->getAttribute('enableLogin', false)) {
        //     return new RedirectResponse('/');
        // }
        /** @var LazySession */
        $session  = $request->getAttribute('session');
        $redirect = $this->getRedirect($request, $session);
        // handle supported Http Method types, proxy to the correct handler method
        return match($request->getMethod()) {
            Http::METHOD_GET    => $this->handleGet($request),
            Http::METHOD_POST   => $this->handlePost($request, $session, $redirect),
            default => new EmptyResponse(405), // defaults to a method not allowed
        };

        // Display initial login form
        $session->set(self::REDIRECT_ATTRIBUTE, $redirect);

        // Render and return a response:
        return new HtmlResponse($this->renderer->render(
            'user-manager::login',
        ));
    }

    private function handlePost(
        ServerRequestInterface $request,
        SessionInterface $session,
        string $redirect
    ): ResponseInterface {
        // remove this before we attempt to authenticate since user session takes precendence
        $session->unset(UserInterface::class);

        $this->form->setValidationGroup(['email', 'password']);
        $this->form->setData($request->getParsedBody());

        if (! $this->form->isValid()) {
            return new HtmlResponse($this->renderer->render(
                    'user-manager::login',
                    ['form' => $this->form] // parameters to pass to template
            ));
        }

        if ($this->adapter->authenticate($request)) {
            $session->unset(self::REDIRECT_ATTRIBUTE);
            return new RedirectResponse($redirect);
        }

        return new HtmlResponse(
            $this->renderer->render(
                'user-manager::login',
                ['form' => $this->form]
            ),
            401
        );
    }

    protected function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $this->renderer->render(
                'user-manager::login',
                ['form' => $this->form]
            )
        );
    }

    private function getRedirect(
        ServerRequestInterface $request,
        SessionInterface $session
    ): string {
        /** @var string */
        //$redirect = $session->get(self::REDIRECT_ATTRIBUTE);
        $redirect = $this->config['redirect'];

        if (! $redirect) {
            $uri      = new Uri($request->getHeaderLine('Referer'));
            $redirect = $uri->getPath();
        }
        return $redirect;
    }
}
