<?php

return [
    "multi" => [
        "tester" => [
            'driver' => 'eloquent',
            'model' => 'App\Tester',
            'table' => 'testers',
        ],
        "developer" => [
            'driver' => 'eloquent',
            'model' => 'App\Developer',
            'table' => 'developers',
        ],
    ],
    'password' => [
        'email' => 'partials.email.reminder',
        'table' => 'password_resets',
        'expire' => 60,
    ]
];
