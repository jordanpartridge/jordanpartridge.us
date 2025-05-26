<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gmail API Credentials
    |--------------------------------------------------------------------------
    |
    | Your Gmail API client ID and client secret, obtained from the
    | Google Developer Console.
    |
    */
    'client_id'     => env('GMAIL_CLIENT_ID'),
    'client_secret' => env('GMAIL_CLIENT_SECRET'),

    // Use the value from .env file directly without using route() helper
    // This will be properly set in AppServiceProvider after routes are loaded
    'redirect_uri' => env('GMAIL_REDIRECT_URI'),

    /*
    |--------------------------------------------------------------------------
    | Gmail API Scopes
    |--------------------------------------------------------------------------
    |
    | The scopes requested when authenticating with Google.
    | See https://developers.google.com/gmail/api/auth/scopes for available scopes.
    |
    */
    'scopes' => [
        'https://www.googleapis.com/auth/gmail.readonly',
        'https://www.googleapis.com/auth/gmail.modify', // Required for star toggle functionality
        'https://www.googleapis.com/auth/gmail.labels', // Required for label management
        // 'https://www.googleapis.com/auth/gmail.send',
        // 'https://www.googleapis.com/auth/gmail.compose',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default From Email
    |--------------------------------------------------------------------------
    |
    | The default email to use as the sender when sending emails.
    |
    */
    'from_email' => env('GMAIL_FROM_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | Store Tokens in Database
    |--------------------------------------------------------------------------
    |
    | Whether to store access tokens in the database.
    |
    */
    'store_tokens' => true,

    /*
    |--------------------------------------------------------------------------
    | Auto Refresh Tokens
    |--------------------------------------------------------------------------
    |
    | Whether to automatically refresh tokens when they expire.
    |
    */
    'auto_refresh_tokens' => true,

    /*
    |--------------------------------------------------------------------------
    | Token Cache Time
    |--------------------------------------------------------------------------
    |
    | Time in minutes to cache tokens.
    |
    */
    'token_cache_time' => 60,

    /*
    |--------------------------------------------------------------------------
    | Branded Email Template
    |--------------------------------------------------------------------------
    |
    | Path to a custom branded email template to use when sending emails.
    | Leave null to use the default Gmail template.
    |
    */
    'branded_template' => null,

    /*
    |--------------------------------------------------------------------------
    | Auto Register Routes
    |--------------------------------------------------------------------------
    |
    | Whether to automatically register routes for authentication.
    | If true, this will register routes for OAuth redirect and callback.
    |
    */
    'register_routes' => false, // Disabled - using custom controller for database storage

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | The prefix for the routes registered by this package.
    | Routes will be registered as: /{prefix}/auth/redirect and /{prefix}/auth/callback
    |
    */
    'route_prefix' => 'gmail',

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | The middleware to apply to the routes registered by this package.
    | Typically you'd want to include 'web' for session support and any
    | additional middleware like 'auth' if needed.
    |
    */
    'route_middleware' => ['web'],
];
