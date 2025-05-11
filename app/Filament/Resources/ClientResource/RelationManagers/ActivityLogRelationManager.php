<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $recordTitleAttribute = 'description';

    protected static ?string $title = 'Activity History';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('description')
                    ->label('Activity')
                    ->searchable(),

                TextColumn::make('causer.name')
                    ->label('User')
                    ->searchable()
                    ->default('System'),

                TextColumn::make('properties')
                    ->label('Details')
                    ->formatStateUsing(function ($state) {
                        $changes = [];
                        $attributes = $state['attributes'] ?? [];
                        $old = $state['old'] ?? [];

                        // Only display changed attributes
                        foreach ($attributes as $key => $value) {
                            $oldValue = $old[$key] ?? null;
                            if ($value != $oldValue) {
                                $formattedValue = is_array($value) ? json_encode($value) : $value;
                                $formattedOldValue = is_array($oldValue) ? json_encode($oldValue) : $oldValue;

                                // Format the change
                                $changes[] = "<strong>{$key}</strong>: " .
                                    ($oldValue ? "{$formattedOldValue} → " : "") .
                                    "{$formattedValue}";
                            }
                        }

                        return implode('<br>', $changes);
                    })
                    ->html(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->modifyQueryUsing(function (Builder $query) {
                // Only get activities for this specific client
                return $query->where('log_name', 'client');
            });
    }
}
