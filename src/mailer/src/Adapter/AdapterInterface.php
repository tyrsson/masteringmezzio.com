<?php

declare(strict_types=1);

namespace Mailer\Adapter;

interface AdapterInterface
{
    public function to(string $email, string $name);
    public function from(string $email);
    public function body(string $body);
    public function altBody(string $altBody);
    public function subject(string $subject);
    public function cc(string $email);
    public function bcc(string $email);
    public function isHtml(bool $flag = true);
    public function isSmtp(bool $flag = false);
    //public function send();
}
