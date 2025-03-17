<?php

namespace App\Filament\Resources\AISettingResource\Pages;

use App\Filament\Resources\AISettingResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAISettings extends ListRecords
{
    protected static string $resource = AISettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
