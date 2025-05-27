<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gmail Integration Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the Gmail integration
    | feature, including pagination, caching, and performance settings.
    |
    */

    'pagination' => [
        'default_page_size' => env('GMAIL_DEFAULT_PAGE_SIZE', 10),
        'max_page_size'     => env('GMAIL_MAX_PAGE_SIZE', 50),
        'max_unread_fetch'  => env('GMAIL_MAX_UNREAD_FETCH', 1000),
        'fetch_multiplier'  => env('GMAIL_FETCH_MULTIPLIER', 2), // Fetch extra to account for filtering
    ],

    'cache' => [
        'stats_duration'          => env('GMAIL_CACHE_STATS_DURATION', 300), // 5 minutes
        'messages_duration'       => env('GMAIL_CACHE_MESSAGES_DURATION', 180), // 3 minutes
        'labels_duration'         => env('GMAIL_CACHE_LABELS_DURATION', 600), // 10 minutes
        'client_matches_duration' => env('GMAIL_CACHE_CLIENT_MATCHES_DURATION', 900), // 15 minutes
    ],

    'content' => [
        'snippet_max_length'     => env('GMAIL_SNIPPET_MAX_LENGTH', 150),
        'subject_max_length'     => env('GMAIL_SUBJECT_MAX_LENGTH', 100),
        'sender_name_max_length' => env('GMAIL_SENDER_NAME_MAX_LENGTH', 50),
    ],

    'classification' => [
        'github_domains' => [
            'github.com',
            'notifications@github.com',
            'noreply@github.com',
        ],
        'laravel_keywords' => [
            'laravel',
            'artisan',
            'eloquent',
            'blade',
        ],
        'service_domains' => [
            'noreply',
            'no-reply',
            'donotreply',
            'support@',
            'help@',
        ],
        'github_pr_keywords' => [
            'pull request',
            'pr ',
            'merged',
            'review requested',
        ],
        'github_issue_keywords' => [
            'issue',
            'bug',
            'feature request',
        ],
        'github_action_keywords' => [
            'action',
            'workflow',
            'build',
            'deploy',
            'ci/cd',
            'test',
        ],
    ],

    'actions' => [
        'confirm_delete'         => env('GMAIL_CONFIRM_DELETE', true),
        'auto_mark_read_on_view' => env('GMAIL_AUTO_MARK_READ_ON_VIEW', false),
        'archive_after_delete'   => env('GMAIL_ARCHIVE_AFTER_DELETE', true),
    ],

    'github_integration' => [
        'extract_urls'       => env('GMAIL_GITHUB_EXTRACT_URLS', true),
        'max_urls_per_email' => env('GMAIL_GITHUB_MAX_URLS', 3),
        'notifications_url'  => env('GITHUB_NOTIFICATIONS_URL', 'https://github.com/notifications'),
    ],

    'client_matching' => [
        'enabled'         => env('GMAIL_CLIENT_MATCHING', true),
        'match_by_domain' => env('GMAIL_MATCH_BY_DOMAIN', true),
        'cache_matches'   => env('GMAIL_CACHE_CLIENT_MATCHES', true),
        'fuzzy_matching'  => env('GMAIL_FUZZY_MATCHING', false),
    ],

    'security' => [
        'sanitize_html'     => env('GMAIL_SANITIZE_HTML', true),
        'allowed_html_tags' => [
            'p', 'br', 'strong', 'em', 'u', 'ol', 'ul', 'li', 'a', 'img',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'code', 'pre',
            'table', 'tr', 'td', 'th', 'tbody', 'thead', 'tfoot',
            'div', 'span', 'font', 'b', 'i', 'center', 'hr'
        ],
        'max_email_size'           => env('GMAIL_MAX_EMAIL_SIZE', 1048576), // 1MB
        'enable_rich_content'      => env('GMAIL_ENABLE_RICH_CONTENT', true),
        'block_external_images'    => env('GMAIL_BLOCK_EXTERNAL_IMAGES', false),
        'bypass_purifier_on_error' => env('GMAIL_BYPASS_PURIFIER_ON_ERROR', true),
        'marketing_email_mode'     => env('GMAIL_MARKETING_EMAIL_MODE', true),
        'strip_dangerous_tags'     => env('GMAIL_STRIP_DANGEROUS_TAGS', true),
    ],

    'performance' => [
        'lazy_load_content'  => env('GMAIL_LAZY_LOAD_CONTENT', true),
        'prefetch_next_page' => env('GMAIL_PREFETCH_NEXT_PAGE', false),
        'batch_operations'   => env('GMAIL_BATCH_OPERATIONS', true),
    ],

    'logging' => [
        'log_api_calls'      => env('GMAIL_LOG_API_CALLS', false),
        'log_performance'    => env('GMAIL_LOG_PERFORMANCE', false),
        'log_errors'         => env('GMAIL_LOG_ERRORS', true),
        'log_client_matches' => env('GMAIL_LOG_CLIENT_MATCHES', false),
    ],
];
