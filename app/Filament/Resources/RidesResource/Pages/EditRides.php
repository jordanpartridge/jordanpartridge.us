<?php

namespace App\Filament\Resources\RidesResource\Pages;

use App\Filament\Resources\RidesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRides extends EditRecord
{
    protected static string $resource = RidesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
