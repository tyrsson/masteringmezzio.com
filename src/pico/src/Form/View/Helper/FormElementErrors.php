<?php

declare(strict_types=1);

namespace Pico\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\View\Helper\FormElementErrors as LaminasHelper;

use function str_contains;
use function str_replace;

final class FormElementErrors extends LaminasHelper
{
    /** @var array Default attributes for the open format tag */
    protected $attributes = ['class' => 'start-xs'];
    /** @inheritDoc */
    public function render(ElementInterface $element, array $attributes = []): string
    {
        $messages = $element->getMessages();
        if (! $messages) {
            return '';
        }
        $name = $element->getAttribute('name');
        $name = str_contains($name, '[') ? str_replace(['[', ']'], '-', $name) . 'helper' : $name . '-helper';

        $element->setAttributes(
            [
                'aria-invalid'     => 'true',
                'aria-describedby' => $name,
            ]
        );
        $attributes['id'] = $name;
        return parent::render($element, $attributes);
    }
}
