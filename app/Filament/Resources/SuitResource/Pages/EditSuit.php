<?php

namespace App\Filament\Resources\SuitResource\Pages;

use App\Filament\Resources\SuitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuit extends EditRecord
{
    protected static string $resource = SuitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
