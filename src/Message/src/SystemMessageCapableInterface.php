<?php

declare(strict_types=1);

namespace Message;

interface SystemMessageCapableInterface
{
    public const EVENT_SYSTEM_MESSAGE = 'systemMessage';
    public const SYSTEM_MESSAGE_KEY   = self::EVENT_SYSTEM_MESSAGE;
    /**
     * Set the $key to be used by the SystemMessenger for storing this $message
     */
    public function setSystemMessageKey(string $systemMessageKey): self;
    public function getSystemMessageKey(): string;
    /**
     * Set the systemMessage $message
     */
    public function setSystemMessage(string $systemMessage): self;
    public function getSystemMessage(): ?string;
    /**
     * Number of hops the systemMessage will be available for in the session
     */
    public function setHops(int $hops = 1): self;
    public function getHops(): int;
    /**
     * Flag systemMessage as accessible in current request
     */
    public function setNow(bool $now = true): self;
    public function getNow(): bool;
    /** Set flag to for extending classes to send notification */
    public function setNotify(bool $flag = true): self;
    public function getNotify(): bool;
}
