<?php

declare(strict_types=1);

namespace UserManager\Form\Fieldset;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Password;
use Laminas\Validator\StringLength;
use Webinertia\Validator\Password as PasswordValidator;

final class ChangePasswordFieldset extends PasswordFieldset
{
    public function __construct($name = 'acct-data', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        parent::init();
        $this->add([
            'name' => 'current_password',
            'type' => Password::class,
            'options' => [
                'label' => 'Password',
            ],
            ['priority' => 1],
        ]);
    }

    public function getInputFilterSpecification(): array
    {
        $spec    = parent::getInputFilterSpecification();
        $options = $this->getOptions();
        $spec[] = [
            'name'       => 'current_password',
            'required'   => true,
            'filters'    => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 100,
                    ],
                ],
                [
                    'name' => PasswordValidator::class,
                    'options' => [
                        $options['password_options'],
                    ],
                ],
            ],
        ];
        return $spec;
    }
}
