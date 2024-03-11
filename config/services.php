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
    'apple_pay' => [
        'merchantId' => env('APPLE_PAY_MERCHANT_ID', 'merchant.placetopay-test'),
        'domainName' => env('APPLE_PAY_DOMAIN_NAME', 'applepay-e9tjn.ondigitalocean.app'),
        'displayName' => env('APPLE_PAY_DISPLAY_NAME','Test Placetopay'),
        'ssl_key_password' => env('APPLE_PAY_SSL_KEY_PASSWORD', 'Admin123')
    ]
];
