<?php

namespace Database\Seeders;

use App\Settings\GitHubSettings;
use Illuminate\Database\Seeder;

class GitHubSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = app(GitHubSettings::class);

        // Initialize with empty values if not set
        if (empty($settings->username)) {
            $settings->username = 'jordanpartridge';
        }

        $settings->save();
    }
}
