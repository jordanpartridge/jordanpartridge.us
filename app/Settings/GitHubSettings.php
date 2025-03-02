<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GitHubSettings extends Settings
{
    public ?string $token;
    public string $username;

    public static function group(): string
    {
        return 'github';
    }

    public function getToken(): ?string
    {
        return $this->token ? decrypt($this->token) : null;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token ? encrypt($token) : null;
    }

    /**
     * Get a setting value by key
     * This is a convenience method for backward compatibility
     */
    public static function get(string $key, $default = null)
    {
        $settings = app(self::class);
        return $settings->$key ?? $default;
    }
}
