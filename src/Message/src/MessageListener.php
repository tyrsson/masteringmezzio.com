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
use UserManager\ConfigProvider;
use UserManager\Event\MessageEvent as UserMessage;
use UserManager\Helper\VerificationHelper;
use UserManager\User\Message;

use function sprintf;

final class MessageListener extends AbstractListenerAggregate
{
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
            Event\MessageEvent::EVENT_UI_MESSAGE,
            [$this, 'onUiMessage'],
            $priority
        );
    }

    public function onUiMessage(EventInterface $e)
    {
        // handle ui messages via flash messages
    }
}
