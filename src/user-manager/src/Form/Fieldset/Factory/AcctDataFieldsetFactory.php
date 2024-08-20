<?php

declare(strict_types=1);

namespace UserManager\Form\Fieldset\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use User\Db\UserGateway;
use User\Form\Fieldset\AcctDataFieldset;

final class AcctDataFieldsetFactory implements FactoryInterface
{
    /**
     * @param string $requestedName
     * @param null|mixed[] $options
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AcctDataFieldset
    {
        return new $requestedName($container->get(UserGateway::class), $options);
    }
}
