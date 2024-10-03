<?php

declare(strict_types=1);

use Laminas\Db\Adapter\AdapterInterface;

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {
    $showDebug = false;
    $adapter   = null;
    if (file_exists('config/development.config.php') && class_exists(\Debug\Debug::class)) {
        $debugConfig = require 'config/development.config.php';

        if ($debugConfig['debug']) {
            $showDebug = true;
            \Debug\Debug::timer('total-runtime');
        }
    }

    /** @var \Psr\Container\ContainerInterface $container */
    $container = require 'config/container.php';
    if ($showDebug) {
        $adapter = $container->get(AdapterInterface::class);
    }

    /** @var \Mezzio\Application $app */
    $app = $container->get(\Mezzio\Application::class);
    $factory = $container->get(\Mezzio\MiddlewareFactory::class);

    // Execute programmatic/declarative middleware pipeline and routing
    // configuration statements
    (require 'config/pipeline.php')($app, $factory, $container);

    $app->run();
    if ($showDebug) {
        echo \Debug\Debug::timer('total-runtime');
        echo \Debug\Debug::dbDebug($adapter);
    }
})();
