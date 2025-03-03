<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GithubRepositoryResource\Pages\ListGithubRepositories;
use App\Filament\Resources\GithubRepositoryResource\Pages\CreateGithubRepository;
use App\Filament\Resources\GithubRepositoryResource\Pages\EditGithubRepository;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use App\Models\GithubRepository;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Exception;

class GithubRepositoryResource extends Resource
{
    protected static ?string $model = GithubRepository::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Repository Details')
                    ->schema([
                        TextInput::make('name'),
                        TextInput::make('repository'),
                        Textarea::make('description'),
                        TextInput::make('url')->url(),
                    ]),
                Section::make('Display Options')
                    ->schema([
                        Toggle::make('featured'),
                        Toggle::make('is_active'),
                        TextInput::make('display_order')->numeric(),
                    ]),
                Section::make('Repository Stats')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('stars_count')->numeric(),
                                TextInput::make('forks_count')->numeric(),
                            ]),
                        TagsInput::make('technologies')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('description'),
                TextColumn::make('stars_count'),
                TextColumn::make('forks_count'),
                IconColumn::make('featured'),
                IconColumn::make('is_active'),
                TextColumn::make('last_fetched_at')->datetime()->since(),
            ])
            ->filters([
                TernaryFilter::make('featured'),
                TernaryFilter::make('is_active'),
                SelectFilter::make('technologies')
                    ->options(function () {
                        $technologies = GithubRepository::all()
                            ->pluck('technologies')
                            ->flatten()
                            ->unique()
                            ->filter()
                            ->values()
                            ->toArray();
                        return array_combine($technologies, $technologies);
                    })
            ])
            ->actions([
                Action::make('sync')
                    ->action(function (GithubRepository $record) {
                        try {
                            Notification::make()
                                ->title('Repository Synced')
                                ->success()
                                ->send();
                        } catch (Exception $e) {
                            Notification::make()
                                ->title('Sync Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('sync_selected')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                try {
                                    Notification::make()
                                        ->title('Selected Repositories Synced')
                                        ->success()
                                        ->send();
                                } catch (Exception $e) {
                                    Notification::make()
                                        ->title('Bulk Sync Failed')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }
                        }),
                    DeleteBulkAction::make()
                ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListGithubRepositories::route('/'),
            'create' => CreateGithubRepository::route('/create'),
            'edit'   => EditGithubRepository::route('/{record}/edit')
        ];
    }
}
