<?php

declare(strict_types=1);

namespace UserManager\Form\Fieldset;

use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Text;
use Laminas\Form\Fieldset;
use Laminas\Hydrator\ArraySerializableHydrator;
use UserManager\UserRepository\UserEntity;

final class AcctDataFieldset extends Fieldset
{
    public function init(): void
    {
        $this->setHydrator(new ArraySerializableHydrator());
        $this->setObject(new UserEntity());

        $this->add([
            'name' => 'id',
            'type' => Hidden::class,
        ])->add([
            'name' => 'firstName',
            'type' => Text::class,
            'attributes' => [
                'placeholder' => 'First Name',
            ],
        ])->add([
            'name' => 'lastName',
            'type' => Text::class,
            'attributes' => [
                'placeholder' => 'Last Name',
            ],
        ])->add([
            'name'    => 'email',
            'type'    => Text::class,
            'attributes' => [
                'placeholder' => 'Email',
            ],
        ]);
    }
}
