<?php

declare(strict_types=1);

namespace UserManager\InputFilter;

use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\InputFilter\InputFilter;
use Laminas\Filter;
use Laminas\Validator;
use UserManager\UserRepository\TableGateway as UserRepository;

class AcctDataFilter extends InputFilter implements AdapterAwareInterface
{
    use AdapterAwareTrait;

    public function __construct(
        private UserRepository $repo
    ) {
    }

    public function init(): void
    {
        $this->add(
            [
                'name'        => 'id',
                'allow_empty' => true,
                'filters'     => [
                    ['name' => Filter\ToInt::class],
                    ['name' => Filter\ToNull::class],
                ],
            ]
        );
        $this->add(
            [
                'name'     => 'firstName',
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
                            'max'      => 50, // true, we may never see an email this length, but they are still valid
                        ],
                    ],
                ],
            ]
        );
        $this->add(
            [
                'name'     => 'lastName',
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
                            'max'      => 50, // true, we may never see an email this length, but they are still valid
                        ],
                    ],
                ],
            ]
        );
        $this->add(
            [
                'name' => 'email',
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
                            'max'      => 320, // true, we may never see an email this length, but they are still valid
                        ],
                    ],
                    // @see EmailAddress for $options
                    ['name' => Validator\EmailAddress::class],
                    [
                        'name'    => Validator\Db\NoRecordExists::class,
                        'options' => [
                            'table'     => $this->repo->getTable(),
                            'field'     => 'email',
                            'dbAdapter' => $this->adapter,
                            'messages'  => [
                                Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'Email is already in use!!',
                            ],
                        ],
                    ],
                ],
            ]
        );

    }
}
