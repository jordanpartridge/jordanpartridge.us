<?php

namespace App\Filament\Resources\AISettingResource\Pages;

use App\Filament\Resources\AISettingResource;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAISetting extends EditRecord
{
    protected static string $resource = AISettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
