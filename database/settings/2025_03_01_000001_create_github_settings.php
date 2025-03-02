<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGithubSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('github.token', null);
        $this->migrator->add('github.username', 'jordanpartridge');
    }
}