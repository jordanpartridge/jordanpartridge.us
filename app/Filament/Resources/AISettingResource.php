<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AISettingResource\Pages\ListAISettings;
use App\Filament\Resources\AISettingResource\Pages\CreateAISetting;
use App\Filament\Resources\AISettingResource\Pages\EditAISetting;
use App\Models\AISetting;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AISettingResource extends Resource
{
    protected static ?string $model = AISetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                Textarea::make('value')
                    ->nullable(),
                TextInput::make('description')
                    ->nullable()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->searchable(),
                TextColumn::make('value')
                    ->limit(50),
                TextColumn::make('description')
                    ->limit(30),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAISettings::route('/'),
            'create' => CreateAISetting::route('/create'),
            'edit'   => EditAISetting::route('/{record}/edit'),
        ];
    }
}
