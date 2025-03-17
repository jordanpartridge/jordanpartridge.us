<?php

namespace App\Filament\Resources\SocialPostResource\Pages;

use App\Filament\Resources\SocialPostResource;
use App\Services\SocialMediaService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateSocialPost extends CreateRecord
{
    protected static string $resource = SocialPostResource::class;
    
    protected function afterCreate(): void
    {
        // Get the record we just created
        $record = $this->record;
        
        // Check if auto-share is enabled
        if ($this->data['auto_share'] ?? false) {
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
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
