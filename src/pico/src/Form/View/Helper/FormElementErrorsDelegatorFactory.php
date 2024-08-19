<?php

declare(strict_types=1);

namespace Pico\Form\View\Helper;

use Laminas\ServiceManager\Factory\DelegatorFactoryInterface;
use Psr\Container\ContainerInterface;

final class FormElementErrorsDelegatorFactory implements DelegatorFactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, callable $callback, ?array $options = null): FormElementErrors
    {
        $helper = new FormElementErrors();

        if (! $container->has('config')) {
            return $helper;
        }

        $config = $container->get('config');
        if (isset($config['view_helper_config']['form_element_errors'])) {
            $configHelper =
                $config['view_helper_config']['form_element_errors'];

            // Attributes
            if (
                isset($configHelper['attributes'])
                && is_array($configHelper['attributes'])
            ) {
                $helper->setAttributes($configHelper['attributes']);
            }

            // Message close string
            if (array_key_exists('message_close_string', $configHelper)) {
                $helper->setMessageCloseString(
                    $configHelper['message_close_string']
                );
            }

            // Message open format
            if (array_key_exists('message_open_format', $configHelper)) {
                $helper->setMessageOpenFormat(
                    $configHelper['message_open_format']
                );
            }

            // Message separator string
            if (array_key_exists('message_separator_string', $configHelper)) {
                $helper->setMessageSeparatorString(
                    $configHelper['message_separator_string']
                );
            }

            // Translate error messages
            if (array_key_exists('translate_messages', $configHelper)) {
                $helper->setTranslateMessages(
                    $configHelper['translate_messages']
                );
            }
        }
        return $helper;
    }
}
