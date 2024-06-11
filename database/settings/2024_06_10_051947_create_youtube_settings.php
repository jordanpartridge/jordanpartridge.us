<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class () extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add(
            'featured_podcast.url',
            'https://www.youtube.com/embed/iT5j2fsemWc?si=7gxee82OSjWmYoBu'
        );

        $this->migrator->add(
            'featured_podcast.title',
            'Doing the Work Every Day'
        );


        $this->migrator->add(
            'featured_podcast.description',
            'Listen as Scott Foley explains the value of doing the work and working on your goals every day.'
        );
    }
};
