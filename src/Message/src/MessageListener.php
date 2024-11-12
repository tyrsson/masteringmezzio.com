<?php

declare(strict_types=1);

namespace Message;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Mailer\MailerInterface;
use Mezzio\Helper\UrlHelper;

use function sprintf;

final class MessageListener extends AbstractListenerAggregate
{
    private const DEFAULT_MESSAGE = 'The requested action was performed';

    private SystemMessengerInterface $messenger;

    public function __construct(
        private MailerInterface $mailer,
        private UrlHelper $urlHelper,
        private array $config
    ) {
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            SystemMessage::EVENT_SYSTEM_MESSAGE,
            [$this, 'onSystemMessage'],
            $priority
        );
    }

    /**
     * Setup the generic SystemMessenger
     */
    public function onSystemMessage(SystemMessage $e): void
    {
        $message = $e->getSystemMessage() ?? static::DEFAULT_MESSAGE;
        if ($e->getNow()) {
            $this->messenger->sendNow($e->getSystemMessageKey(), $message, $e->getHops());
        } else {
            $this->messenger->send($e->getSystemMessageKey(), $message, $e->getHops());
        }
    }

    public function setSystemMessenger(SystemMessengerInterface $systemMessengerInterface): void
    {
        $this->messenger = $systemMessengerInterface;
    }
}
