<?php

declare(strict_types=1);

namespace Mail\Adapter;

use PHPMailer\PHPMailer\PHPMailer as BaseMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Psr\Container\ContainerInterface;

final class PhpMailerFactory
{
    public function __invoke(ContainerInterface $container): PhpMailer
    {
        $config = $container->get('config');
        return new PhpMailer(new BaseMailer());
    }
}
