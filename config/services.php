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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => '508229399730707',
        'client_secret' => '52d71ead51b2f983a8d7cfa69e5ce0d6',
        'redirect' => 'https://recipessss.herokuapp.com/api/social-auth/facebook/callback',
    ],
    'google' => [
        'client_id' => '731390877098-u03ed2i0dscj9mm20cjge9svqrp1ojju.apps.googleusercontent.com',
        'client_secret' => 'mGoqktFRJvg0iWqsczOraHEM',
        'redirect' => '/api/social-auth/google/callback',
    ],

];
