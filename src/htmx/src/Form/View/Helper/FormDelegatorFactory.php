<?php

declare(strict_types=1);

namespace Htmx\Form\View\Helper;

use Htmx\HtmxAttributes as Attribs;
use Laminas\Form\View\Helper\Form;
use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;
use Psr\Container\ContainerInterface;

class FormDelegatorFactory implements DelegatorFactoryInterface
{
    /** @inheritDoc */
    public function __invoke(
        ContainerInterface $container,
        $name,
        callable $callback,
        ?array $options = null
    ): Form {
        /** @var Form */
        $formHelper = $callback();
        foreach(Attribs::cases() as $attrib) {
            $formHelper->addValidAttribute($attrib->value);
        }

        return $formHelper;
    }
}
