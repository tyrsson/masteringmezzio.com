<?php

declare(strict_types=1);

namespace UserManager\Form;

use Htmx\HtmxAttributes as Htmx;

final class ResendVerification extends Register
{
    public function init(): void
    {
        parent::init();
        $this->setAttributes([
            Htmx::HX_Post->value   => $this->urlHelper->generate('user-manager.verify'),
            Htmx::HX_Target->value => '#app-main',
        ]);
        $fieldset = $this->getBaseFieldset();
        $fieldset->remove('firstName')
        ->remove('lastName')
        ->remove('email')
        ->remove('password')
        ->remove('conf_password');
        $submit = $this->get('Register');
        $submit->setValue('Resend Verification');
    }
}
