<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GithubRepositoryResource\Pages;
use App\Models\GithubRepository;
use App\Services\GitHub\GitHubSyncService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GithubRepositoryResource extends Resource
{
    protected static ?string $model = GithubRepository::class;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';
    
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Repository Details')
                    ->description('Basic information about the repository')
                    ->icon('heroicon-o-book-open')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->autofocus(),
                        Forms\Components\TextInput::make('repository')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Repository name without owner (e.g., "github-client")')
                            ->placeholder('github-client'),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(500)
                            ->placeholder('A brief description of the repository...')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('url')
                            ->required()
                            ->url()
                            ->placeholder('https://github.com/jordanpartridge/github-client')
                            ->helperText('Full URL to the GitHub repository')
                            ->suffixIcon('heroicon-m-link')
                            ->maxLength(255),
                    ]),
                
                Forms\Components\Section::make('Display Options')
                    ->description('How this repository appears on your website')
                    ->icon('heroicon-o-eye')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Toggle::make('featured')
                            ->default(false)
                            ->helperText('Featured repositories appear on your website')
                            ->onIcon('heroicon-s-star')
                            ->offIcon('heroicon-s-star'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive repositories are not synced with GitHub')
                            ->onIcon('heroicon-s-check-circle')
                            ->offIcon('heroicon-s-x-circle'),
                        Forms\Components\TextInput::make('display_order')
                            ->numeric()
                            ->default(0)
                            ->step(1)
                            ->minValue(0)
                            ->helperText('Lower numbers display first (0 is highest priority)'),
                    ]),
                
                Forms\Components\Section::make('Repository Stats')
                    ->description('Statistics from GitHub (automatically updated when synced)')
                    ->icon('heroicon-o-chart-bar')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('stars_count')
                                    ->label('Stars')
                                    ->numeric()
                                    ->default(0)
                                    ->prefixIcon('heroicon-o-star'),
                                Forms\Components\TextInput::make('forks_count')
                                    ->label('Forks')
                                    ->numeric()
                                    ->default(0)
                                    ->prefixIcon('heroicon-o-arrow-path'),
                            ]),
                        Forms\Components\TagsInput::make('technologies')
                            ->placeholder('Add technology')
                            ->helperText('Languages and technologies used in this repository')
                            ->columnSpanFull()
                            ->suggestions([
                                'PHP', 'Laravel', 'JavaScript', 'TypeScript', 'Vue.js', 'React', 
                                'Python', 'Go', 'Rust', 'C#', 'Java', 'Ruby', 'CSS', 'HTML', 
                                'API', 'DevOps', 'CI/CD', 'Testing', 'Docker', 'AWS', 
                                'Frontend', 'Backend', 'Database', 'Mobile', 'Web'
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->repository),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->sortable()
                    ->label('Featured')
                    ->alignCenter()
                    ->color('primary'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('stars_count')
                    ->label('Stars')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->icon('heroicon-o-star')
                    ->iconPosition('before'),
                Tables\Columns\TextColumn::make('forks_count')
                    ->label('Forks')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->icon('heroicon-o-arrow-path')
                    ->iconPosition('before'),
                Tables\Columns\TextColumn::make('last_fetched_at')
                    ->label('Synced')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('featured', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Show Featured')
                    ->indicator('Featured'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Show Active')
                    ->indicator('Active'),
                Tables\Filters\SelectFilter::make('technologies')
                    ->multiple()
                    ->options(function () {
                        $technologies = \App\Models\GithubRepository::all()
                            ->flatMap(fn ($repo) => $repo->technologies ?? [])
                            ->unique()
                            ->sort()
                            ->mapWithKeys(fn ($tech) => [$tech => $tech]);
                            
                        return $technologies;
                    })
                    ->query(function ($query, array $data) {
                        if (empty($data['values'])) {
                            return $query;
                        }
                        
                        return $query->where(function ($query) use ($data) {
                            foreach ($data['values'] as $value) {
                                $query->orWhereJsonContains('technologies', $value);
                            }
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('sync')
                    ->label('Sync')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (GithubRepository $record): void {
                        $syncService = app(GitHubSyncService::class);
                        $success = $syncService->syncRepository($record);
                        
                        if ($success) {
                            \Filament\Notifications\Notification::make()
                                ->title('Repository synced')
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Failed to sync repository')
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('sync_selected')
                        ->label('Sync Selected')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            $syncService = app(GitHubSyncService::class);
                            $success = 0;
                            
                            foreach ($records as $record) {
                                if ($syncService->syncRepository($record)) {
                                    $success++;
                                }
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title("Synced {$success} of {$records->count()} repositories")
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGithubRepositories::route('/'),
            'create' => Pages\CreateGithubRepository::route('/create'),
            'edit' => Pages\EditGithubRepository::route('/{record}/edit'),
        ];
    }
}