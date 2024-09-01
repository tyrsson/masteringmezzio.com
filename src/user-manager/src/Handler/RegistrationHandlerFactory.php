<?php

declare(strict_types=1);

namespace UserManager\Handler;

use Laminas\Form\FormElementManager;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;
use UserManager\Form\Register;

class RegistrationHandlerFactory
{
    public function __invoke(ContainerInterface $container) : RegistrationHandler
    {
        $manager = $container->get(FormElementManager::class);
        return new RegistrationHandler(
            $container->get(TemplateRendererInterface::class),
            $manager->get(Register::class)
        );
    }
}
