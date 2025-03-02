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
}