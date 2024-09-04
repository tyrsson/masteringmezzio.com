<?php

declare(strict_types=1);

namespace UserManager\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;
use UserManager\ConfigProvider;

final class AcctDataFilterFactory
{
    public function __invoke(ContainerInterface $container): AcctDataFilter
    {
        $config = $container->get('config');
        $filter = new AcctDataFilter(
            $config[ConfigProvider::class][ConfigProvider::USERMANAGER_TABLE_NAME],
            $config['authentication']['username']
        );
        $filter->setDbAdapter($container->get(AdapterInterface::class));
        return $filter;
    }
}
