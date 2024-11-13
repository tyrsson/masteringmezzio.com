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
use Message\View\Helper\SystemMessenger as Helper;

final class MessageMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EventManagerInterface $em,
        private MessageListener $messageListener,
        private Helper $helper
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // create an instance of the SystemMessenger
        $systemMessenger = new SystemMessenger(
            $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE),
            SystemMessengerInterface::SESSION_KEY
        );
        // inject the SystemMessenger into the listener instance
        $this->messageListener->setSystemMessenger($systemMessenger);
        // attach the default listener
        $this->messageListener->attach($this->em);
        // inject SystemMessenger into the helper instance
        $this->helper->setMessenger($systemMessenger);
        // next in the stack
        return $handler->handle($request->withAttribute(EventManagerInterface::class, $this->em));
    }
}
