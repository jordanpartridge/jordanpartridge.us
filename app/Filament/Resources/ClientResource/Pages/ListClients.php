<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    public function getHeader(): ?View
    {
        $header = parent::getHeader();

        return view('filament.resources.client-resource.pages.client-list-header', [
            'actions'    => $header?->getActions(),
            'heading'    => $header?->getHeading(),
            'subheading' => $header?->getSubheading(),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add any widgets you might want above the list
        ];
    }
}
