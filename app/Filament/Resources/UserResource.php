<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                    Wizard\Step::make('Basic Information')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),

                        ]),
                    Wizard\Step::make('Avatar')
                        ->schema([
                            FileUpload::make('avatar')
                                ->image()
                                ->required()
                                ->maxSize(1024),
                        ]),
                    Wizard\Step::make('Password')
                        ->schema([
                            TextInput::make('password')
                                ->password()
                                ->confirmed()
                                ->dehydrated(fn ($state) => filled($state))
                                ->helperText('Leave empty to keep the current password')
                                ->maxLength(255)
                                ->autocomplete(false),

                            TextInput::make('password_confirmation')
                                ->password()
                                ->maxLength(255),
                        ]),
                ]),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()->sortable(),
                Tables\Columns\ImageColumn::make('avatar')->label('Profile Picture'),
                Tables\Columns\TextColumn::make('name')->searchable()->label('Full Name'),
                Tables\Columns\TextColumn::make('email')->searchable()->label('Email Address'),
                Tables\Columns\TextColumn::make('created_at')->date()->label('Created At'),
                Tables\Columns\TextColumn::make('updated_at')->date()->label('Updated At'),
            ])
            ->filters([
                // Add any filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
