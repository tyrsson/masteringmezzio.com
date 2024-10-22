<?php

declare(strict_types=1);

return [
    'routes' => [
        0 => [
            'path' => '/crud',
            'name' => 'Crud',
            'middleware' => [
                0 => \App\Handler\CrudHandler::class,
            ],
            'allowed_methods' => [
                0 => 'DELETE',
                1 => 'GET',
                2 => 'POST',
                3 => 'PUT',
            ],
        ],
        1 => [
            'path' => '/test-another',
            'name' => 'Test Another',
            'middleware' => [
                0 => \App\Handler\TestAnotherHandler::class,
            ],
            'allowed_methods' => [
                0 => 'DELETE',
                1 => 'GET',
                2 => 'POST',
                3 => 'PUT',
            ],
        ],
    ],
    'mezzio-authorization-rbac' => [
        'permissions' => [
            'Guest' => [
                0 => 'Crud',
                1 => 'Test Another',
            ],
        ],
    ],

];