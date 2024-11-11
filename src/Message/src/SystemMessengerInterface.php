<?php

declare(strict_types=1);

namespace Message;

interface SystemMessengerInterface
{
    public const SESSION_KEY = self::class . '::SYSTEM_MESSENGER_NEXT';

    public function send(string $key, string $message, int $hops = 1): void;
    public function sendNow(string $key, string $message, int $hops = 1): void;
    public function getMessage(string $key, $default = null);
    public function getMessages(): array;
    public function clearMessages(): void;
    public function addHop(): void;
}
