<?php

declare(strict_types=1);

namespace UserManager\Form;

use Htmx\HtmxAttributes as Htmx;
use Htmx\Form\HtmxTrait;
use Laminas\Filter;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;
use Laminas\Form;

final class Login extends Form\Form implements InputFilterProviderInterface
{
    use HtmxTrait;

    protected $attributes = ['class' => 'login-form'];

    /** @inheritDoc */
    public function __construct($name = 'login-form', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        $this->setAttributes([
            Htmx::HX_Post->value => $this->urlHelper->generate('user-manager.login'),
            Htmx::HX_Target->value => '#app-main'
        ]);
        $this->add([
            'name' => 'email',
            'type' => Form\Element\Text::class,
            'attributes' => [
                //'placeholder' => 'User Name',
            ],
            'options' => [
                'label' => 'Email',
            ],
        ]);
        $this->add([
            'name' => 'password',
            'type' => Form\Element\Password::class,
            'attributes' => [
                //'placeholder' => 'Email',
            ],
            'options' => [
                'label' => 'Password',
            ],
        ]);
        $this->add([
            'name' => 'Login',
            'type' => Form\Element\Submit::class,
            'attributes' => [
                'value' => 'Login',
            ],
        ]);
    }

    public function getInputFilterSpecification(): array
    {
        return [
            'email' => [
                'required' => true,
                'filters'  => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ],
                    ],
                ],
            ],
            'password' => [
                'required' => true,
            ],
        ];
    }
}
