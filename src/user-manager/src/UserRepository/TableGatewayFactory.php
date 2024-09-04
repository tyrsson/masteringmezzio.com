<?php

declare(strict_types=1);

namespace UserManager\UserRepository;

use Axleus\Db;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Hydrator\ArraySerializableHydrator;
use Mezzio\Authentication\UserInterface;
use Psr\Container\ContainerInterface;

final class TableGatewayFactory
{
    public function __invoke(ContainerInterface $container): TableGateway
    {
        $hydrator = new ArraySerializableHydrator();
        return new TableGateway(
            gateway: new Db\TableGateway(
                'users',
                $container->get(AdapterInterface::class),
                null,
                new HydratingResultSet(
                    $hydrator,
                    new UserEntity()
                )
            ),
            userFactory: $container->get(UserInterface::class),
            hydrator: $hydrator,
            config: $container->get('config')['authentication']
        );
    }
}
