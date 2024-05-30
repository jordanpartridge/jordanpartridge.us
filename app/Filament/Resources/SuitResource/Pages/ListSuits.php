<?php

namespace App\Filament\Resources\SuitResource\Pages;

use App\Filament\Resources\SuitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuits extends ListRecords
{
    protected static string $resource = SuitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
