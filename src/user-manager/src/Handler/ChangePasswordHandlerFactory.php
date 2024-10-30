<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Laminas\Form\FormElementManager;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use UserManager\Form\ChangePassword;

class ChangePasswordHandlerFactory
{
    public function __invoke(ContainerInterface $container): ChangePasswordHandler
    {
        $fm = $container->get(FormElementManager::class);

        return new ChangePasswordHandler(
            $container->get(TemplateRendererInterface::class),
            $fm->get(ChangePassword::class)
        );
    }
}
