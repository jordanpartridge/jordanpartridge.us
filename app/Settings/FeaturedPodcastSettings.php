<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FeaturedPodcastSettings extends Settings
{
    /**
     * @var string
     */
    public string $url;

    /**
     * @var string
     */
    public string $title;

    /**
     * @var string
     */
    public string $description;

    /**
     * @return string
     */
    public static function group(): string
    {
        return 'featured_podcast';
    }
}
