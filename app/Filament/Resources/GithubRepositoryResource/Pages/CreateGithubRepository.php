<?php

namespace App\Filament\Resources\GithubRepositoryResource\Pages;

use App\Filament\Resources\GithubRepositoryResource;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateGithubRepository extends CreateRecord
{
    protected static string $resource = GithubRepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('fetch_from_github')
                ->label('Fetch from GitHub')
                ->color('primary')
                ->form([
                    TextInput::make('username')
                        ->required(),
                    TextInput::make('repository')
                        ->required()
                ])
                ->action(function (array $data) {
                    try {
                        // GitHub fetch logic
                        Notification::make()
                            ->title('Repository Fetched Successfully')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Fetch Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
        ];
    }
}
