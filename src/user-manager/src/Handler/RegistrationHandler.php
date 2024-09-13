<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Fig\Http\Message\RequestMethodInterface as Http;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mailer\Adapter\AdapterInterface;
use Mailer\ConfigProvider as MailConfigProvider;
use Mailer\Adapter\PhpMailer;
use Mailer\Mailer;
use Mailer\MailerInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UserManager\ConfigProvider;
use UserManager\Form\Register;
use UserManager\UserRepository\TableGateway;
use Webinertia\Filter\PasswordHash;

use function sprintf;

class RegistrationHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private UserRepositoryInterface&TableGateway $userRepositoryInterface,
        private Register $form,
        private UrlHelper $urlHelper,
        private array $config
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
            try {
                $serverParams = $request->getServerParams();
                $userEntity->hashPassword();
                $result       = $this->userRepositoryInterface->save($userEntity, 'id');
                /** @var Mailer */
                $mailer = $request->getAttribute(MailerInterface::class);
                /** @var PhpMailer */
                $adapter = $mailer->getAdapter();
                $mailConfig = $this->config[MailConfigProvider::class][AdapterInterface::class] ?? null;
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
            } catch (\Throwable $th) {
                throw $th;
            }
            return new RedirectResponse(
                $this->urlHelper->generate('home')
            );
        }
        return new HtmlResponse($this->renderer->render(
            'user-manager::registration',
            ['form' => $this->form] // parameters to pass to template
        ));
    }
}
