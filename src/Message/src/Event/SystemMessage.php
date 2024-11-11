<?php

declare(strict_types=1);

namespace Message\Event;

final class SystemMessage extends MessageEvent
{
    public const EVENT_SYSTEM_MESSAGE = 'systemMessage';

    private string $key  = self::EVENT_SYSTEM_MESSAGE;
    private int    $hops = 1;
    private bool   $now  = false;
    private ?string $message = null;

    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setHops(int $hops = 1): self
    {
        $this->hops = $hops;
        return $this;
    }

    public function getHops(): int
    {
        return $this->hops;
    }

    public function now(bool $flag = true): bool
    {
        if ($flag !== $this->now) {
            $this->now = $flag;
        }
        return $this->now;
    }
}
