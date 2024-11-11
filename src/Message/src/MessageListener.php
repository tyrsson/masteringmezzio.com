<?php

declare(strict_types=1);

namespace Message;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\EventInterface;
use Mailer\Adapter\AdapterInterface;
use Mailer\ConfigProvider as MailConfigProvider;
use Mailer\Event\MessageEvent as EmailMessage;
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
        //$this->listeners[] = $events->attach(Message::Email->value, [$this, 'onEmailMessage'], $priority);
        $this->listeners[] = $events->attach(
            Event\SystemMessage::EVENT_SYSTEM_MESSAGE,
            [$this, 'onSystemMessage'],
            $priority
        );
    }

    /**
     * Setup the generic SystemMessenger
     */
    public function onSystemMessage(Event\SystemMessage $e): void
    {
        $message = $e->getMessage() ?? static::DEFAULT_MESSAGE;
        if ($e->now()) {
            $this->messenger->sendNow($e->getKey(), $message, $e->getHops());
        } else {
            $this->messenger->send($e->getKey(), $message, $e->getHops());
        }
    }

    public function setSystemMessenger(SystemMessengerInterface $systemMessengerInterface): void
    {
        $this->messenger = $systemMessengerInterface;
    }
}
