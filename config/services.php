<?php

return [

    // Stock clips for the app ("videos de apoyo"). https://pixabay.com/api/docs/
    // "IA de Lote": Lote's own OpenAI key, used for Pro subscribers.
    'openai' => [
        'key' => env('OPENAI_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-5-mini'),
    ],

    // Lets the developer's own (unsigned) builds use the AI without a receipt.
    'lote' => [
        'dev_token' => env('LOTE_AI_DEV_TOKEN'),
    ],

    'pixabay' => [
        'key' => env('PIXABAY_KEY'),
    ],

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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

];
