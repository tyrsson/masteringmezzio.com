<?php

declare(strict_types=1);

namespace Debug;

use Laminas\Db\Adapter\AdapterInterface;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'delegators' => [
                AdapterInterface::class => [
                    Db\AdapterInterfaceDelegator::class,
                ],
            ],
        ];
    }
}
