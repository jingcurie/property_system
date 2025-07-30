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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'docusign' => [
        'client_id' => env('DOCUSIGN_CLIENT_ID'),
        'user_id' => env('DOCUSIGN_USER_ID'),
        'account_id' => env('DOCUSIGN_ACCOUNT_ID'),
        'keypair_id' => env('DOCUSIGN_KEYPAIR_ID'),
        'base_url' => env('DOCUSIGN_BASE_URL'),
        'oauth_base_url' => env('DOCUSIGN_OAUTH_BASE_URL'),
        'private_key_path' => storage_path('app/docusign/private.key'),
    ],

];
