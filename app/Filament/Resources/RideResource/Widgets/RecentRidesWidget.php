<?php

namespace App\Filament\Resources\RideResource\Widgets;

use App\Models\Ride;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentRidesWidget extends BaseWidget
{
    protected static ?int $sort = 2; // Position after your streak widget

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ride::query()
                    ->where('created_at', '>=', now()->subWeek())
                    ->latest()
            )
            ->columns([
                TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('distance')
                    ->suffix(' miles')
                    ->numeric(
                        decimalPlaces: 1,
                    )
                    ->sortable(),
                TextColumn::make('duration')
                    ->formatStateUsing(
                        fn (string $state): string => gmdate('H:i:s', $state)
                    )
                    ->label('Time'),
                TextColumn::make('average_speed')
                    ->suffix(' mph')
                    ->numeric(
                        decimalPlaces: 1,
                    ),
            ])
            ->paginated(false);
    }
}
