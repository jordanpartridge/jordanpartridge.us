<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RidesResource\Pages\CreateRides;
use App\Filament\Resources\RidesResource\Pages\EditRides;
use App\Filament\Resources\RidesResource\Pages\ListRides;
use App\Models\Ride;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RidesResource extends Resource
{
    protected static ?string $model = Ride::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Ride Name')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('date')
                    ->label('Ride Date')
                    ->required(),
                TextInput::make('distance')
                    ->label('Distance (km)')
                    ->required()
                    ->numeric(),
                TextInput::make('duration')
                    ->label('Duration (min)')
                    ->required()
                    ->numeric(),
                TextInput::make('average_speed')
                    ->label('Avg Speed (km/h)')
                    ->required()
                    ->numeric(),
                TextInput::make('max_speed')
                    ->label('Max Speed (km/h)')
                    ->numeric(),
                TextInput::make('calories')
                    ->label('Calories Burned')
                    ->numeric(),
                TextInput::make('elevation')
                    ->label('Elevation Gain (m)')
                    ->numeric(),
                Textarea::make('polyline')
                    ->label('Map Polyline')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Ride Date')
                    ->date()
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->color('primary'),
                TextColumn::make('name')
                    ->label('Ride Name')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-academic-cap')
                    ->color('success'),
                BadgeColumn::make('distance')
                    ->label('Distance (km)')
                    ->sortable()
                    ->colors([
                        'danger'  => static fn ($state): bool => $state > 32,
                        'warning' => static fn ($state): bool => $state <= 32 && $state > 20,
                        'success' => static fn ($state): bool => $state <= 20,
                    ]),
                TextColumn::make('duration')
                    ->label('Duration (min)')
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->color('secondary'),
                TextColumn::make('average_speed')
                    ->label('Avg Speed (km/h)')
                    ->sortable()
                    ->icon('heroicon-o-arrows-up-down')
                    ->color('info'),
            ])
            ->filters([

            ])
            ->actions([
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),
                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
            ])
            ->paginated();
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
            'index'  => ListRides::route('/'),
            'create' => CreateRides::route('/create'),
            'edit'   => EditRides::route('/{record}/edit'),
        ];
    }
}
