<?php

namespace App\Filament\Resources\SocialPostResource\Pages;

use App\Filament\Resources\SocialPostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSocialPosts extends ListRecords
{
    protected static string $resource = SocialPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
