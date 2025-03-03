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
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
        'verification' => [
            'token' => env('SLACK_VERIFICATION_TOKEN'),
        ],
    ],
    'strava' => [
        'client_id'     => env('STRAVA_CLIENT_ID'),
        'client_secret' => env('STRAVA_CLIENT_SECRET'),
        'scope'         => 'read,activity:read_all',
        'authorize_url' => 'https://www.strava.com/oauth/authorize',
    ],
    'card_api' => [
        'api_key'  => env('CARD_API_KEY'),
        'base_url' => env('CARD_API_BASE_URL'),
    ],
    'google_maps' => [
        'key' => env('GOOGLE_MAP_API_KEY'),
    ],
    'github' => [
        'token'    => env('GITHUB_API_TOKEN'),
        'username' => env('GITHUB_USERNAME', 'jordanpartridge'),
    ],
];
