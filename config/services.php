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

    /*
    |--------------------------------------------------------------------------
    | Prism AI Service Configuration
    |--------------------------------------------------------------------------
    |
    | This section configures the Prism PHP package for AI content generation.
    |
    */
    'prism' => [
        // API Connection Settings
        'api_key'  => env('PRISM_API_KEY'),
        'base_url' => env('PRISM_BASE_URL', 'https://api.prism.ai'),
        'timeout'  => env('PRISM_REQUEST_TIMEOUT', 30),

        // Default Model Settings
        'model'          => env('PRISM_DEFAULT_MODEL', 'ollama/mistral:latest'),
        'fallback_model' => env('PRISM_FALLBACK_MODEL', 'ollama/mistral:7b-instruct-q4_0'),

        // Content Generation Parameters
        'default_temperature' => env('PRISM_DEFAULT_TEMPERATURE', 0.7),
        'default_max_tokens'  => env('PRISM_DEFAULT_MAX_TOKENS', 500),

        // Prompt Storage
        'template_storage' => env('PRISM_TEMPLATE_STORAGE', 'database'), // Options: database, json, yaml
        'template_path'    => env('PRISM_TEMPLATE_PATH', database_path('ai/templates')),

        // Logging & Debug
        'log_level'     => env('PRISM_LOG_LEVEL', 'info'), // Options: debug, info, warning, error
        'log_prompts'   => env('PRISM_LOG_PROMPTS', false),
        'log_responses' => env('PRISM_LOG_RESPONSES', false),
    ],
];
