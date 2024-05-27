<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RideResource\Pages;
use App\Models\Ride;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RideResource extends Resource
{
    /**
      * @var string|null The model the resource corresponds to.
      */
    protected static ?string $model = Ride::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('date')->dateTime('Y-m-d h:i A')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('distance')->label('Distance (Miles)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('elevation')->label('Elevation Gain (Feet)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('average_speed')->label('Average Speed (MPH)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('max_speed')->label('Max Speed (MPH)')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

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
            'index'  => Pages\ListRides::route('/'),
            'create' => Pages\CreateRide::route('/create'),
            'edit'   => Pages\EditRide::route('/{record}/edit'),
            'view'   => Pages\ViewRide::route('/{record}'),
        ];
    }
}
