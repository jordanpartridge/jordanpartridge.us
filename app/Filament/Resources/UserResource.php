<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Basic Information')
                        ->schema([
                            FileUpload::make('avatar')
                                ->avatar()
                                ->maxSize(1024),

                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),

                            Textarea::make('bio')
                                ->label('Biography')
                                ->helperText('A short bio to display on blog posts you author')
                                ->maxLength(500)
                                ->columnSpanFull(),
                        ]),
                    Step::make('Password')
                        ->schema([
                            TextInput::make('password')
                                ->password()
                                ->confirmed()
                                ->dehydrated(fn ($state) => filled($state))
                                ->required(fn ($context) => $context === 'create')
                                ->helperText('Leave empty to keep the current password')
                                ->maxLength(255)
                                ->autocomplete(false),

                            TextInput::make('password_confirmation')
                                ->password()
                                ->required(fn ($context) => $context === 'create')
                                ->maxLength(255),
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable()->sortable(),
                ImageColumn::make('avatar')->label('Avatar')->circular(),
                TextColumn::make('name')->searchable()->label('Full Name'),
                TextColumn::make('email')->searchable()->label('Email Address'),
                TextColumn::make('bio')
                    ->label('Biography')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->date()->label('Created At'),
                TextColumn::make('updated_at')->date()->label('Updated At'),
            ])
            ->filters([
                // Add any filters if needed
            ])
            ->actions([
                EditAction::make()->slideOver(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define any relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            //            'create' => Pages\CreateUser::route('/create'),
            //            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
