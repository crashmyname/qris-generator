<?php

return [

    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('SMTP_HOST', 'smtp.mailtrap.io'),
            'port' => env('SMTP_PORT', 587),
            'encryption' => env('SMTP_SECURE', 'tls'),
            'username' => env('SMTP_EMAIL'),
            'password' => env('SMTP_PASSWORD'),
        ],
        'sendmail' => [
            'transport' => 'sendmail',
            'path' => '/usr/sbin/sendmail -bs',
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
        'name' => env('MAIL_FROM_NAME', 'BPJS App'),
    ],

    'log_channel' => env('MAIL_LOG_CHANNEL'),

];
