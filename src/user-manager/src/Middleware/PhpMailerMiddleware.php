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
    private const SUBJECT_KEY = 'verification_subject';
    private const BODY_KEY    = 'verification_body';
    private const TEMPLATE_KEY = 'message_templates';

    public function __construct(
        private MailerInterface&PhpMailer $mailerInterface,
        private array $config
    ){
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RouteResult */
        $routeResult = $request->getAttribute(RouteResult::class);
        $routeName   = $routeResult->getMatchedRouteName();
        if ($routeName === 'user-manager.register' && $request->getMethod() === Http::METHOD_POST) {
            if (! empty($this->config[ConfigProvider::class][PhpMailer::class])) {
                $config = $this->config[ConfigProvider::class][PhpMailer::class];
            }
            $host = 'http://masteringmezzio.com';

            /** @var PhpMailer */
            $mailer  = clone $this->mailerInterface;
            //$subject =
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
        return $handler->handle($request);
    }
}
