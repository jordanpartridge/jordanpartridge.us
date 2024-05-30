<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuitResource\Pages;
use App\Models\Suit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SuitResource extends Resource
{
    protected static ?string $model = Suit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->placeholder('Enter the name of the suit'),
                Forms\Components\TextInput::make('color')
                    ->label('Color')
                    ->required()
                    ->placeholder('Enter the color of the suit'),
                Tables\Columns\TextColumn::make('symbol')
                    ->label('Symbol')
                    ->required()
                    ->placeholder('Enter the symbol of the suit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('color'),
                Tables\Columns\TextColumn::make('symbol')->label('Icon'),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSuits::route('/'),
            'create' => Pages\CreateSuit::route('/create'),
            'edit'   => Pages\EditSuit::route('/{record}/edit'),
        ];
    }
}
