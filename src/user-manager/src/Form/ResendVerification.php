<?php

declare(strict_types=1);

namespace UserManager\Form;

use Laminas\Filter;
use Laminas\Form;
use Laminas\Form\Element;
use Laminas\Hydrator\ArraySerializableHydrator;
use Htmx\HtmxAttributes as Htmx;
use Htmx\Form\HtmxTrait;



final class ResendVerification extends Form\Form
{
    use HtmxTrait;

    public function init(): void
    {
        $this->add([
            'name' => 'acct-data',
            'type' => Fieldset\ResendVerification::class
        ]);

        $this->add([
            'name'       => 'resend-verify',
            'type'       => Form\Element\Submit::class,
            'attributes' => [
                'value' => 'Resend Verification',
            ],
        ]);
    }
}
