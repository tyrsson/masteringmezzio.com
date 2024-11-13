<?php

declare(strict_types=1);

namespace Message;

use Mezzio\Session\SessionInterface;

/**
 * original code by Mezzio\Flash
 * @package Message
 */
class SystemMessenger implements SystemMessengerInterface
{
    /** @var array<string,mixed> */
    private array $currentMessages = [];

    public function __construct(private SessionInterface $session, private string $sessionKey)
    {
        $this->prepareMessages($session, $sessionKey);
    }

    /**
     * Set a Message value with the given key.
     *
     * Message values are accessible on the next "hop", where a hop is the
     * next time the session is accessed; you may pass an additional $hops
     * integer to allow access for more than one hop.
     *
     * @param mixed $message
     * @throws Exception\InvalidHopsValueException
     */
    public function send(string $key, $message, int $hops = 1): void
    {
        if ($hops < 1) {
            throw Exception\InvalidHopsValueException::valueTooLow($key, $hops);
        }

        $messages       = $this->getStoredMessages();
        $messages[$key] = [
            'message' => $message,
            'hops'  => $hops,
        ];
        $this->session->set($this->sessionKey, $messages);
    }

    /**
     * Set a Message value with the given key, but allow access during this request.
     *
     * Message values are generally accessible only on subsequent requests;
     * using this method, you may make the value available during the current
     * request as well.
     *
     * If you want the value to be visible only in the current request, you may
     * pass zero as the third argument.
     *
     * @param mixed $message
     */
    public function sendNow(string $key, string $message, int $hops = 1): void
    {
        $this->currentMessages[$key] = $message;
        if ($hops > 0) {
            $this->send($key, $message, $hops);
        }
    }

    /**
     * Retrieve a message value.
     *
     * Will return a value only if a message value was set in a previous request,
     * or if `sendNow()` was called in this request with the same `$key`.
     *
     * WILL NOT return a value if set in the current request via `send()`.
     *
     * @param mixed $default Default value to return if no message value exists.
     * @return mixed
     */
    public function getMessage(string $key, $default = null)
    {
        return $this->currentMessages[$key] ?? $default;
    }

    /**
     * Retrieve all message values.
     *
     * Will return all values was set in a previous request, or if `sendNow()`
     * was called in this request.
     *
     * WILL NOT return values set in the current request via `send()`.
     */
    public function getMessages(): array
    {
        return $this->currentMessages;
    }

    /**
     * Clear all message values.
     *
     * Affects the next and subsequent requests.
     */
    public function clearMessages(): void
    {
        $this->session->unset($this->sessionKey);
    }

    /**
     * Prolongs any current messages for one more hop.
     */
    public function addHop(): void
    {
        $messages = $this->getStoredMessages();

        /** @var mixed $message */
        foreach ($this->currentMessages as $key => $message) {
            if (isset($messages[$key])) {
                continue;
            }

            $this->send($key, $message);
        }
    }

    public function prepareMessages(SessionInterface $session, string $sessionKey): void
    {
        if (! $session->has($sessionKey)) {
            return;
        }

        $sessionMessages = $this->getStoredMessages($sessionKey);

        /** @var array<string,mixed> $currentMessages */
        $currentMessages = [];
        foreach ($sessionMessages as $key => $data) {

            $currentMessages[$key] = $data['message'];

            if ($data['hops'] === 1) {
                unset($sessionMessages[$key]);
                continue;
            }

            $data['hops']         -= 1;
            $sessionMessages[$key] = $data;
        }

        empty($sessionMessages)
            ? $session->unset($sessionKey)
            : $session->set($sessionKey, $sessionMessages);

        $this->currentMessages = $currentMessages;
    }

    /**
     * @return StoredMessages
     */
    private function getStoredMessages(?string $sessionKey = null): array
    {
        /** @var StoredMessages|null $messages */
        $messages = $this->session->get($sessionKey ?? $this->sessionKey, []);
        return $messages ?? [];
    }
}
