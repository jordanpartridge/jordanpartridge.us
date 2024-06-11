<?php

namespace App\Settings;

class FeaturedPodcastSettings extends \Spatie\LaravelSettings\Settings
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
