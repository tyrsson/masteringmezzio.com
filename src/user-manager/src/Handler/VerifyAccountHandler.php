<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Fig\Http\Message\RequestMethodInterface as Http;
use Htmx\HtmxAttributes as Htmx;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mailer\Adapter\AdapterInterface;
use Mailer\Adapter\PhpMailer;
use Mailer\ConfigProvider as MailerConfigProvider;
use Mailer\Mailer;
use Mailer\MailerInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UserManager\ConfigProvider;
use UserManager\Form\ResendVerification;
use UserManager\Helper\VerificationHelper;
use UserManager\UserRepository\TableGateway;
use UserManager\UserRepository\UserEntity;

class VerifyAccountHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private UserRepositoryInterface&TableGateway $userRepositoryInterface,
        private MailerInterface&Mailer $mailer,
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
        try {
            $body = $request->getParsedBody();
            $this->form->setData($body);
            if ($this->form->isValid()) {
                $userEntity = $this->form->getData();
                $serverParams = $request->getServerParams();
                $result = $this->userRepositoryInterface->save($userEntity, 'id');
                $result = $this->userRepositoryInterface->findOneBy('id', $result->id);
                /** @var Mailer */
                $mailer = $request->getAttribute(MailerInterface::class);
                /** @var PhpMailer */
                $adapter = $mailer->getAdapter();
                $mailConfig = $this->config[MailerConfigProvider::class][AdapterInterface::class] ?? null;
                $adapter?->to(
                    $result->email,
                    $result->firstName . ' ' . $result->lastName
                );
                $adapter?->isHtml();
                $adapter?->subject(
                    sprintf(
                        $mailConfig[ConfigProvider::MAIL_MESSAGE_TEMPLATES][ConfigProvider::MAIL_VERIFY_SUBJECT],
                        $this->config['app_settings']['app_name']
                    )
                );
                $adapter?->body(
                    sprintf(
                        $mailConfig[ConfigProvider::MAIL_MESSAGE_TEMPLATES][ConfigProvider::MAIL_VERIFY_MESSAGE_BODY],
                        $serverParams['REQUEST_SCHEME'] . '://' . $serverParams['HTTP_HOST'],
                        $this->urlHelper->generate(
                            routeName: 'user-manager.verify',
                            routeParams: [
                                'id'    => $result->id,
                                'token' => $result->verificationToken,
                            ],
                            options: ['reuse_query_params' => false]
                        )
                    )
                );
                $mailer?->send($adapter);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return new RedirectResponse(
            $this->urlHelper->generate('home')
        );
    }
}
