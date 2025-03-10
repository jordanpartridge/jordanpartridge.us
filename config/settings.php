<?php

return [

    /*
     * Each settings class used in your application must be registered, you can
     * put them (manually) here.
     */
    'settings' => [

    ],

    /*
     * The path where the settings classes will be created.
     */
    'setting_class_path' => app_path('Settings'),

    /*
     * In these directories settings migrations will be stored and ran when migrating. A settings
     * migration created via the make:settings-migration command will be stored in the first path or
     * a custom defined path when running the command.
     */
    'migrations_paths' => [
        database_path('settings'),
    ],

    /*
     * When no repository was set for a settings class the following repository
     * will be used for loading and saving settings.
     */
    'default_repository' => 'database',

    /*
     * Settings will be stored and loaded from these repositories.
     * The configuration options determine how and where settings are stored.
     */
    'repositories' => [
        'database' => [
            'type'       => Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository::class,
            'model'      => null, // Uses the default model if null
            'table'      => null, // Uses 'settings' table if null
            'connection' => null, // Uses the default database connection if null
        ],
        'redis' => [
            'type'       => Spatie\LaravelSettings\SettingsRepositories\RedisSettingsRepository::class,
            'connection' => null, // Uses the default Redis connection if null
            'prefix'     => null, // No prefix is applied to Redis keys if null
        ],
    ],

    /*
     * The encoder and decoder will determine how settings are stored and
     * retrieved in the database. By default, `json_encode` and `json_decode`
     * are used.
     */
    'encoder' => null,
    'decoder' => null,

    /*
     * The contents of settings classes can be cached through your application,
     * settings will be stored within a provided Laravel store and can have an
     * additional prefix.
     *
     * Caching is enabled by default in production for better performance.
     * It can be explicitly enabled/disabled using the SETTINGS_CACHE_ENABLED
     * environment variable.
     */
    'cache' => [
        'enabled' => env('SETTINGS_CACHE_ENABLED', env('APP_ENV') === 'production'),
        'store'   => null,
        'prefix'  => null,
        'ttl'     => null,
    ],

    /*
     * These global casts will be automatically used whenever a property within
     * your settings class isn't a default PHP type.
     */
    'global_casts' => [
        DateTimeInterface::class => Spatie\LaravelSettings\SettingsCasts\DateTimeInterfaceCast::class,
        DateTimeZone::class      => Spatie\LaravelSettings\SettingsCasts\DateTimeZoneCast::class,
        // The DataTransferObject cast is commented out as we're using the newer Laravel Data package
        // Uncomment if you need to use the older DTO package
        // Spatie\DataTransferObject\DataTransferObject::class => Spatie\LaravelSettings\SettingsCasts\DtoCast::class,
        Spatie\LaravelData\Data::class => Spatie\LaravelSettings\SettingsCasts\DataCast::class,
    ],

    /*
     * The package will look for settings in these paths and automatically
     * register them.
     */
    'auto_discover_settings' => [
        app_path('Settings'),
    ],

    /*
     * Automatically discovered settings classes can be cached, so they don't
     * need to be searched each time the application boots up.
     */
    'discovered_settings_cache_path' => base_path('bootstrap/cache'),
];
