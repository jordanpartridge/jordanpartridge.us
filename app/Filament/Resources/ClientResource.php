<?php

namespace App\Filament\Resources;

use App\Enums\ClientStatus;
use App\Filament\Resources\ClientResource\Pages\CreateClient;
use App\Filament\Resources\ClientResource\Pages\EditClient;
use App\Filament\Resources\ClientResource\Pages\ListClients;
use App\Filament\Resources\ClientResource\Pages\ViewClient;
use App\Models\Client;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Client Information')
                    ->description('Basic client details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('company')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('website')
                            ->maxLength(255)
                            ->helperText('Example: www.example.com or domain.com')
                            ->placeholder('Enter website domain')
                            ->nullable(),
                    ])->columns(2),

                Section::make('Client Management')
                    ->schema([
                        Select::make('status')
                            ->enum(ClientStatus::class)
                            ->options([
                                ClientStatus::LEAD->value   => 'Lead',
                                ClientStatus::ACTIVE->value => 'Active',
                                ClientStatus::FORMER->value => 'Former',
                            ])
                            ->required()
                            ->default(ClientStatus::LEAD->value)
                            ->native(false),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Account Manager')
                            ->searchable()
                            ->preload(),
                        DatePicker::make('last_contact_at')
                            ->label('Last Contact Date')
                            ->maxDate(now()),
                    ])->columns(3),

                Section::make('Notes')
                    ->schema([
                        RichEditor::make('notes')
                            ->columnSpanFull()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('client-attachments'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('status', 'asc')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('company')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Account Manager')
                    ->sortable(),
                TextColumn::make('last_contact_at')
                    ->date()
                    ->sortable()
                    ->color(fn ($state) => $state && $state->isPast() && $state->diffInDays(now()) > 30 ? 'danger' : null)
                    ->description(fn ($state) => $state ? $state->diffForHumans() : null),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        ClientStatus::LEAD->value   => 'Lead',
                        ClientStatus::ACTIVE->value => 'Active',
                        ClientStatus::FORMER->value => 'Former',
                    ]),
                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Account Manager')
                    ->searchable()
                    ->preload(),
                Filter::make('last_contact')
                    ->form([
                        DatePicker::make('last_contact_from'),
                        DatePicker::make('last_contact_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['last_contact_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('last_contact_at', '>=', $date),
                            )
                            ->when(
                                $data['last_contact_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('last_contact_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('log_contact')
                        ->icon('heroicon-o-phone')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Log contact with client')
                        ->modalDescription(fn (Client $record) => "Record new contact with {$record->name}")
                        ->action(function (Client $record): void {
                            $record->update(['last_contact_at' => now()]);
                        }),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Select::make('status')
                                ->label('New Status')
                                ->options([
                                    ClientStatus::LEAD->value   => 'Lead',
                                    ClientStatus::ACTIVE->value => 'Active',
                                    ClientStatus::FORMER->value => 'Former',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data): void {
                            // Convert Collection to array if it's not already an array
                            if (!is_array($records)) {
                                $records = $records->all();
                            }

                            foreach ($records as $record) {
                                $record->update(['status' => $data['status']]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListClients::route('/'),
            'create' => CreateClient::route('/create'),
            'view'   => ViewClient::route('/{record}'),
            'edit'   => EditClient::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
