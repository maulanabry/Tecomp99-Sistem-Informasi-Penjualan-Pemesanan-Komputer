<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'rajaongkir' => [
        'key' => env('RAJAONGKIR_API_KEY'),
    ],
    'komerce' => [
        'key' => env('KOMERCE_API_KEY'),
        'url' => env('KOMERCE_API_URL', 'https://api-sandbox.collaborator.komerce.id'),
    ],

    'BITESHIP_API_KEY' => env('BITESHIP_API_KEY'),
    'BITESHIP_API_URL' => env('BITESHIP_API_URL'),

    'binderbyte' => [
        'key' => env('BINDERBYTE_API_KEY'),
        'url' => env('BINDERBYTE_API_URL', 'https://api.binderbyte.com'),
    ],
];
