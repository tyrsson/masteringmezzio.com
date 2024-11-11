<?php

declare(strict_types=1);

namespace Message;

use Laminas\EventManager\Event;
use Psr\Http\Message\ServerRequestInterface;

final class SystemMessage extends Event implements SystemMessageCapableInterface
{
    public function setRequest(ServerRequestInterface $request): self
    {
        $this->setParam('request', $request);
        return $this;
    }

    public function getRequest(): ?ServerRequestInterface
    {
        return $this->getParam('request');
    }

    /**
     * Set the $key to be used by the SystemMessenger for storing this $message
     */
    public function setSystemMessageKey(string $systemMessageKey): self
    {
        $this->setParam('systemMessageKey', $systemMessageKey);
        return $this;
    }

    public function getSystemMessageKey(): string
    {
        return $this->getParam('systemMessageKey', self::SYSTEM_MESSAGE_KEY);
    }

    /**
     * Set the systemMessage $message
     */
    public function setSystemMessage(string $systemMessage): self
    {
        $this->setParam(self::SYSTEM_MESSAGE_KEY, $systemMessage);
        return $this;
    }

    public function getSystemMessage(): ?string
    {
        return $this->getParam(self::SYSTEM_MESSAGE_KEY);
    }

    /**
     * Number of hops the systemMessage will be available for in the session
     */
    public function setHops(int $hops = 1): self
    {
        $this->setParam('hops', $hops);
        return $this;
    }

    public function getHops(): int
    {
        return $this->getParam('hops', 1);
    }

    /**
     * Flag systemMessage as accessible in current request
     */
    public function setNow(bool $now = true): self
    {
        $this->setParam('now', $now);
        return $this;
    }

    public function getNow(): bool
    {
        return $this->getParam('now', false);
    }

    public function setNotify(bool $flag = true): self
    {
        $this->setParam('notify', $flag);
        return $this;
    }

    public function getNotify(): bool
    {
        return $this->getParam('notify', false);
    }
}
