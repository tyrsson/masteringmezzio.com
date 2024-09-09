<?php

declare(strict_types=1);

namespace UserManager\Handler;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use Fig\Http\Message\RequestMethodInterface as Http;
use Htmx\HtmxAttributes as Htmx;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mail\MailerInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Template\TemplateRendererInterface;
use PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Rfc4122\UuidV7;
use UserManager\Form\ResendVerification;
use UserManager\Helper\VerificationHelper;
use UserManager\UserRepository\TableGateway;
use UserManager\UserRepository\UserEntity;

class VerifyAccountHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private VerificationHelper $verifyHelper,
        private ResendVerification $form,
        private UrlHelper $urlHelper,
        private array $config
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return match ($request->getMethod()) {
            Http::METHOD_GET => $this->handleGet($request),
            default => $this->handlePost($request)
        };
    }

    public function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        try {
            if (! $this->verifyHelper->verify($request)) {
                /** @var UserEntity */
                $target = $this->verifyHelper->getTarget();
                // unset this to allow a new token to be generated
                $target->offsetUnset('verificationToken');
                // send form to resend email
                $this->form->bind($target);
                $this->form->setAttributes([
                    Htmx::HX_Post->value => $this->urlHelper->generate(
                        routeName: 'user-manager.verify',
                        options: ['reuse_result_params' => false]
                    ),
                    Htmx::HX_Target->value => '#app-main',
                ]);
                return new HtmlResponse($this->renderer->render(
                    'user-manager::verify-account',
                    ['form' => $this->form] // parameters to pass to template
                ));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return new RedirectResponse(
            $this->urlHelper->generate('home')
        );
    }

    public function handlePost(ServerRequestInterface $request): ResponseInterface
    {
        // resend verification email with updated token
    }
}
