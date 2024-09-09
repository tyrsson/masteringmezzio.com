<?php

declare(strict_types=1);

namespace Mailer\Adapter;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Mailer\ConfigProvider;
use PHPMailer\PHPMailer\PHPMailer as BaseMailer;
use Psr\Container\ContainerInterface;

final class PhpMailerFactory
{
    public function __invoke(ContainerInterface $container): PhpMailer
    {
        $config = $container->get('config');
        if (empty($config[ConfigProvider::class][AdapterInterface::class])) {
            throw new ServiceNotCreatedException(
                'Service: ' . PhpMailer::class . ' could not be created. Missing configuration.'
            );
        }
        $config = $config[ConfigProvider::class][AdapterInterface::class];
        $mailer = new BaseMailer(true); // enable exceptions
        //$mailer->isSMTP();
        $mailer->Host     = $config['host'];
        //$mailer->SMTPAuth = $config['smtp_auth'];
        $mailer->Port     = $config['port'];
        $mailer->Username = $config['username'];
        $mailer->Password = $config['password'];
        return new PhpMailer($mailer);
    }
}
