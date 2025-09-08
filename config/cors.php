<?php

return [
    'allow_all_origins' => true,

    'allowed_origins' => [
        true
    ],

    'allowed_methods' => [
        'GET','POST','PUT','DELETE','OPTIONS'
    ],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'X-CSRF-TOKEN',
    ],

    'allowed_credentials' => true,
];