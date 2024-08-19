<?php

declare(strict_types=1);

namespace App\Middleware;

use Laminas\InputFilter\InputFilterPluginManager;
use Psr\Container\ContainerInterface;

class ContactMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ContactMiddleware
    {
        /**
         * This may get a little hard to follow here.
         * The framework exposes a Abstract factory for creating InputFilters
         * from a "spec" (ie an array of configuration) which can be found
         * in the ConfigProvider. Since it makes those avaliable via the
         * input filter plugin manager the following works. 'contact' is
         * the array key used to identify the "spec" to create the filter from
         * @see App\ConfigProvider::getInputFilterSpec() for details
         */
        return new ContactMiddleware(
            ($container->get(InputFilterPluginManager::class))->get('contact')
        );
    }
}
