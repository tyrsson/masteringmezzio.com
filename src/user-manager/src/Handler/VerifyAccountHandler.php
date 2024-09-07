<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
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
use UserManager\Form\ResendVerification;
use UserManager\UserRepository\TableGateway;
use UserManager\UserRepository\UserEntity;

class VerifyAccountHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private UserRepositoryInterface&TableGateway $userRepositoryInterface,
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
        /** @var RouteResult */
        $routeResult   = $request->getAttribute(RouteResult::class);
        $matchedParams = $routeResult->getMatchedParams();
        try {
            /** @var UserEntity */
            $target = $this->userRepositoryInterface->findOneBy('id', $matchedParams['id']);
            if ($target instanceof UserEntity) {
                if ($target->dateCreated === $target->dateUpdated) {

                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }


        return new HtmlResponse($this->renderer->render(
            'user-manager::verify-account',
            ['form' => $this->form] // parameters to pass to template
        ));
    }

    public function handlePost(ServerRequestInterface $request): ResponseInterface
    {
        // resend verification email with updated token
    }
}
