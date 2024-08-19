<?php

declare(strict_types=1);

namespace UserManager\UserRepository;

use Axleus\Db;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\Hydrator\ReflectionHydrator;
use Mezzio\Authentication\UserInterface;
use Psr\Container\ContainerInterface;

final class TableGatewayFactory
{
    public function __invoke(ContainerInterface $container): TableGateway
    {
        return new TableGateway(
            gateway: new Db\TableGateway(
                'users',
                $container->get(AdapterInterface::class),
                null,
                new HydratingResultSet(
                    new ArraySerializableHydrator(),
                    new UserEntity()
                )
            ),
            userFactory: $container->get(UserInterface::class),
            hydrator: null,
            config: $container->get('config')['authentication']
        );
    }
}
