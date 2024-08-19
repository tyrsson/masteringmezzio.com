<?php

declare(strict_types=1);

namespace Pico\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\View\Helper\FormElementErrors as LaminasHelper;

final class FormElementErrors extends LaminasHelper
{
    /** @inheritDoc */
    public function render(ElementInterface $element, array $attributes = []): string
    {
        $messages = $element->getMessages();
        if (! $messages) {
            return '';
        }
        $element->setAttribute('aria-invalid', 'true');
        return parent::render($element, $attributes);
    }
}
