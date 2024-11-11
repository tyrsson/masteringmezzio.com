<?php

declare(strict_types=1);

namespace Message\Middleware;

use Laminas\EventManager\EventManagerInterface;
use Message\MessageListener;
use Message\SystemMessenger;
use Message\SystemMessengerInterface;
use Mezzio\Session\SessionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MessageMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EventManagerInterface $em,
        private MessageListener $messageListener
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->messageListener->setSystemMessenger(
            new SystemMessenger(
                $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE),
                SystemMessengerInterface::SESSION_KEY
            )
        );
        // attach the default listener
        $this->messageListener->attach($this->em);
        return $handler->handle($request->withAttribute(EventManagerInterface::class, $this->em));
    }
}
