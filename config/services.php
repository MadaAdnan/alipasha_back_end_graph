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
        'key' => env('GOOGLE_APP_ID','1:48907040413:web:d9e4ceb3196f41ee078674'),
        'client_secret' => env('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'),
        'client_id'=>env('GOOGLE_CLIENT_ID','48907040413-c15vedugob7dfkfidr2a3fvti0h0pc35.apps.googleusercontent.com'),
//        'redirect'=>env('GOOGLE_REDIRECT','https://pazarpasha.com/oauth/callback/google'),
        'redirect'=>env('GOOGLE_REDIRECT','https://pazarpasha.com/oauth/callback/google'),
    ],

];
