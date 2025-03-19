<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Services\SocialMediaService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function afterCreate(): void
    {
        // Get the record we just created
        $record = $this->record;

        // If post is published and auto-generate is checked
        if (
            $record->status === 'published' &&
            ($this->data['auto_generate_social'] ?? false)
        ) {
            $platforms = $this->data['social_platforms'] ?? [];

            if (!empty($platforms)) {
                $socialService = app(SocialMediaService::class);
                $results = $socialService->postToAllPlatforms($record, $platforms);

                $successful = count(array_filter($results));

                if ($successful > 0) {
                    Notification::make()
                        ->title("Successfully shared to {$successful} platform(s)")
                        ->success()
                        ->send();
                }
            }
        }
    }
}
