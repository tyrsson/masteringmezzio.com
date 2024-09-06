<?php

declare(strict_types=1);

namespace UserManager\Middleware;

use Fig\Http\Message\RequestMethodInterface as Http;
use Mail\Adapter\PhpMailer;
use Mail\ConfigProvider;
use Mail\MailerInterface;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function sprintf;

class PhpMailerMiddleware implements MiddlewareInterface
{
    public final const SUBJECT_KEY  = 'verification_subject';
    public final const BODY_KEY     = 'verification_body';
    public final const TEMPLATE_KEY = 'message_templates';
    public final const FROM_ADDRESS_KEY = 'from_address';

    public function __construct(
        private MailerInterface&PhpMailer $mailerInterface,
        private array $config
    ){
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RouteResult */
        $routeResult = $request->getAttribute(RouteResult::class);

        return match ($routeResult->getMatchedRouteName()) {
            'user-manager.register' => $handler->handle($this->configureRegistration($request)),
            default => $handler->handle($request),
        };
    }

    private function configureRegistration(ServerRequestInterface $request): ServerRequestInterface
    {
        /** @var RouteResult */

        if ($request->getMethod() === Http::METHOD_POST) {
            if (! empty($this->config[ConfigProvider::class][PhpMailer::class])) {
                $config = $this->config[ConfigProvider::class][PhpMailer::class];
            }
            $serverParams = $request->getServerParams();
            $host         = $serverParams['REQUEST_SCHEME'] . '//' . $serverParams['HTTP_HOST'];

            /** @var PhpMailer */
            $mailer  = clone $this->mailerInterface;
            $mailer->setFrom($config[static::FROM_ADDRESS_KEY]);
            $mailer->setSubject(sprintf(
                $config[static::TEMPLATE_KEY][static::SUBJECT_KEY],
                $this->config['app_settings']['app_name']
            ));
            $mailer->setBody(sprintf(
                $config[static::TEMPLATE_KEY][static::BODY_KEY],
                $host,
                '%s'
            ));
            $request = $request->withAttribute(MailerInterface::class, $mailer);
        }
        return $request;
    }
}
