<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

// To enable or disable caching, set the `ConfigAggregator::ENABLE_CACHE` boolean in
// `config/autoload/local.php`.
$cacheConfig = [
    'config_cache_path' => 'data/cache/config-cache.php',
];

$aggregator = new ConfigAggregator([
    \Mezzio\Tooling\ConfigProvider::class,
    \Mezzio\ConfigProvider::class,
    \Mezzio\Session\Ext\ConfigProvider::class,
    \Mezzio\LaminasView\ConfigProvider::class,
    \Mezzio\Helper\ConfigProvider::class,
    \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
    \Mezzio\Authorization\Rbac\ConfigProvider::class,
    \Mezzio\Authorization\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,
    \Mezzio\Authentication\Session\ConfigProvider::class,
    \Mezzio\Authentication\ConfigProvider::class,
    \Mezzio\Session\ConfigProvider::class,
    \Laminas\I18n\ConfigProvider::class,
    \Laminas\HttpHandlerRunner\ConfigProvider::class,
    \Laminas\Diactoros\ConfigProvider::class,
    \Laminas\Form\ConfigProvider::class,
    \Laminas\Hydrator\ConfigProvider::class,
    \Laminas\InputFilter\ConfigProvider::class,
    \Laminas\Filter\ConfigProvider::class,
    \Laminas\Validator\ConfigProvider::class,
    \Laminas\Db\ConfigProvider::class,
    // Include cache configuration
    new ArrayProvider($cacheConfig),
    // load axleus
    \Axleus\Core\ConfigProvider::class,
    \Axleus\Db\ConfigProvider::class,
    \Axleus\Filter\ConfigProvider::class,

    \Axleus\Mailer\ConfigProvider::class,
    \Axleus\Message\ConfigProvider::class,
    \Axleus\Pico\ConfigProvider::class,
    \Axleus\Tooling\ConfigProvider::class,
    \Axleus\UserManager\ConfigProvider::class,
    \Axleus\Validator\ConfigProvider::class,
    // Default App module config
    App\ConfigProvider::class,

    \Axleus\Htmx\ConfigProvider::class,
    /**
     * If DevTools is present load the provider
     */
    class_exists(\Axleus\Debug\ConfigProvider::class)
        ? \Axleus\Debug\ConfigProvider::class
        : function(): array {
            return [];
        },
    // Load application config in a pre-defined order in such a way that local settings
    // overwrite global settings. (Loaded as first to last):
    //   - `global.php`
    //   - `*.global.php`
    //   - `local.php`
    //   - `*.local.php`
    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),
    // Load development config if it exists
    new PhpFileProvider(realpath(__DIR__) . '/development.config.php'),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
