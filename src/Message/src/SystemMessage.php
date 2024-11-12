<?php

declare(strict_types=1);

namespace Message;

use Laminas\EventManager\Event;
use Psr\Http\Message\ServerRequestInterface;

class SystemMessage extends Event implements SystemMessageCapableInterface
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

    public function setSystemMessageKey(string $systemMessageKey): self
    {
        $this->setParam('systemMessageKey', $systemMessageKey);
        return $this;
    }

    public function getSystemMessageKey(): string
    {
        return $this->getParam('systemMessageKey', self::SYSTEM_MESSAGE_KEY);
    }

    public function setSystemMessage(string $systemMessage): self
    {
        $this->setParam(self::SYSTEM_MESSAGE_KEY, $systemMessage);
        return $this;
    }

    public function getSystemMessage(): ?string
    {
        return $this->getParam(self::SYSTEM_MESSAGE_KEY);
    }

    public function setHops(int $hops = 1): self
    {
        $this->setParam('hops', $hops);
        return $this;
    }

    public function getHops(): int
    {
        return $this->getParam('hops', 1);
    }

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
