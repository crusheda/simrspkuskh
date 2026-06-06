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

    'ollama' => [
        'url' => env('OLLAMA_URL'),
        'model' => env('OLLAMA_MODEL', 'mistral'),
    ],

    'bpjs' => [
        'base_url' => env('BPJS_BASE_URL', 'https://apijkn.bpjs-kesehatan.go.id/'),
        'base_url_dev' => env('BPJS_DEV_BASE_URL', 'https://apijkn-dev.bpjs-kesehatan.go.id/'),
        'services_name_vclaim' => env('BPJS_SERVICE_NAME_VCLAIM', 'vclaim-rest'),
        'services_name_icare' => env('BPJS_SERVICE_NAME_ICARE', 'ihs'),
        'services_name_antrean' => env('BPJS_SERVICE_NAME_ANTREAN', 'antreanrs'),
    ],

    'rs' => [
        'base_url' => env('URL_PUBLIC_RS', 'http://192.168.253.30:1137/api/'),
        'username' => env('RS_USERNAME'),
        'password' => env('RS_PASSWORD'),
    ],

];
