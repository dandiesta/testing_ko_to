<?php

return [
    'driver' => 'eloquent',
    'model'  => 'App\UserPass',
    'table'  => 'user_pass',

    'password' => [
        'email' => 'partials.email.reminder',
        'table' => 'password_resets',
        'expire' => 60,
    ]
];
