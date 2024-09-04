<?php

declare(strict_types=1);

namespace UserManager\Form;

use Htmx\HtmxAttributes as Htmx;
use Htmx\Form\HtmxTrait;
use Laminas\Form;
use UserManager\Form\Fieldset\AcctDataFieldset;

class Register extends Form\Form
{
    use HtmxTrait;

    public function init(): void
    {
        $this->setAttributes([
            Htmx::HX_Post->value   => $this->urlHelper->generate('user-manager.register'),
            Htmx::HX_Target->value => '#app-main',
        ]);
        $this->add(
            [
                'name'    => 'acct-data',
                'type'    => AcctDataFieldset::class,
                'options' => [
                    'use_as_base_fieldset' => true,
                ],
            ]
        );

        $this->add([
            'name'       => 'Register',
            'type'       => Form\Element\Submit::class,
            'attributes' => [
                'value' => 'Register',
            ],
        ]);
    }
}
