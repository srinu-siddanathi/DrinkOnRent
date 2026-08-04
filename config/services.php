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

    'msg91' => [
        'auth_key' => env('MSG91_AUTH_KEY'),
        'send_url' => env('MSG91_SEND_URL', 'https://control.msg91.com/api/v5/otp'),
        'resend_url' => env('MSG91_RESEND_URL', 'https://control.msg91.com/api/v5/otp/retry'),
        'sender_id' => env('MSG91_SENDER_ID'),
        'template_id' => env('MSG91_TEMPLATE_ID'),
        'reminder_template_id' => env('MSG91_REMINDER_TEMPLATE_ID'),
        'route' => env('MSG91_ROUTE', '4'),
        'country' => env('MSG91_COUNTRY', '91'),
        'retry_type' => env('MSG91_RETRY_TYPE', 'text'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'razorpay' => [
        'key' => env('RAZORPAY_KEY'),
        'secret' => env('RAZORPAY_SECRET'),
    ],

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

];
