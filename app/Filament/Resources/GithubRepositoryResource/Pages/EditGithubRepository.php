<?php

namespace App\Filament\Resources\GithubRepositoryResource\Pages;

use App\Filament\Resources\GithubRepositoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGithubRepository extends EditRecord
{
    protected static string $resource = GithubRepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync')
                ->label('Sync with GitHub')
                ->icon('heroicon-o-arrow-path')
                ->action(function (): void {
                    $record = $this->getRecord();
                    
                    $syncService = app(\App\Services\GitHub\GitHubSyncService::class);
                    
                    try {
                        $success = $syncService->syncRepository($record);
                        
                        if ($success) {
                            $this->refreshFormData([
                                'name',
                                'description',
                                'stars_count',
                                'forks_count',
                                'technologies',
                                'last_fetched_at',
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Repository synced with GitHub')
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Failed to sync repository')
                                ->body('Repository not found or API error occurred')
                                ->danger()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        if (strpos($e->getMessage(), 'GitHub API token not set') !== false) {
                            // Token not set error
                            \Filament\Notifications\Notification::make()
                                ->title('GitHub API token not set')
                                ->body('You need to set up your GitHub API token in GitHub Settings')
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('settings')
                                        ->label('Go to Settings')
                                        ->url(route('filament.admin.pages.settings.github')),
                                ])
                                ->danger()
                                ->persistent()
                                ->send();
                        } else {
                            // Generic error
                            \Filament\Notifications\Notification::make()
                                ->title('Error syncing repository')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }
                }),
            Actions\DeleteAction::make(),
        ];
    }
}