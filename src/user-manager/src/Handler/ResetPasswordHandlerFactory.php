<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Laminas\Form\FormElementManager;
use Mezzio\Helper\UrlHelper;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use UserManager\Form\ResetPassword;

class ResetPasswordHandlerFactory
{
    public function __invoke(ContainerInterface $container) : ResetPasswordHandler
    {
        $manager = $container->get(FormElementManager::class);
        return new ResetPasswordHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(UrlHelper::class),
            $manager->get(ResetPassword::class)
        );
    }
}
