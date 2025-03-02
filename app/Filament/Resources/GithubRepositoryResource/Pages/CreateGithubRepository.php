<?php

namespace App\Filament\Resources\GithubRepositoryResource\Pages;

use App\Filament\Resources\GithubRepositoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreateGithubRepository extends CreateRecord
{
    protected static string $resource = GithubRepositoryResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('fetch_from_github')
                ->label('Fetch From GitHub')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    \Filament\Forms\Components\TextInput::make('username')
                        ->default(config('services.github.username', 'jordanpartridge'))
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('repository')
                        ->required()
                        ->placeholder('e.g., github-client'),
                ])
                ->action(function (array $data): void {
                    $syncService = app(\App\Services\GitHub\GitHubSyncService::class);
                    
                    try {
                        // Fetch repository from GitHub
                        $repository = $syncService->fetchAndCreateRepository(
                            $data['username'],
                            $data['repository']
                        );
                        
                        if ($repository) {
                            // Redirect to the edit page
                            $this->redirect(static::getResource()::getUrl('edit', ['record' => $repository]));
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Repository fetched successfully')
                                ->success()
                                ->send();
                        } else {
                            // Fill form with basic data if fetch failed
                            $this->fillForm([
                                'name' => $data['repository'],
                                'repository' => $data['repository'],
                                'url' => "https://github.com/{$data['username']}/{$data['repository']}",
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Failed to fetch repository')
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
                                
                            // Fill form with basic data
                            $this->fillForm([
                                'name' => $data['repository'],
                                'repository' => $data['repository'],
                                'url' => "https://github.com/{$data['username']}/{$data['repository']}",
                            ]);
                        } else {
                            // Generic error
                            \Filament\Notifications\Notification::make()
                                ->title('Error fetching repository')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                                
                            // Fill form with basic data
                            $this->fillForm([
                                'name' => $data['repository'],
                                'repository' => $data['repository'],
                                'url' => "https://github.com/{$data['username']}/{$data['repository']}",
                            ]);
                        }
                    }
                }),
        ];
    }
}