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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
        'key' => env('GOOGLE_APP_ID'),
        'client_secret' => env('GOOGLE_SECRET_KEY','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'),
        'client_id'=>env('GOOGLE_CLIENT_ID','890729410482-v6ns5ghs09md1o06al2qq31233tksm4f.apps.googleusercontent.com'),
        'redirect'=>env('GOOGLE_REDIRECT','/seller/oauth/google'),
    ],

];
