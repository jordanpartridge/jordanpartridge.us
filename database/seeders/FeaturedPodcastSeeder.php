<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\LaravelSettings\Models\SettingsProperty;

class FeaturedPodcastSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'featured_podcast.url'         => 'https://www.youtube.com/embed/iT5j2fsemWc?si=7gxee82OSjWmYoBu',
            'featured_podcast.title'       => 'Doing the Work Every Day',
            'featured_podcast.description' => 'Listen as Scott Foley explains the value of doing the work and working on your goals every day.',
        ];

        foreach ($settings as $key => $value) {
            SettingsProperty::updateOrCreate(
                ['group' => 'featured_podcast', 'name' => explode('.', $key)[1]],
                ['payload' => json_encode($value)]
            );
        }
    }
}
