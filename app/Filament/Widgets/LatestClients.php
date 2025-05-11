<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestClients extends BaseWidget
{
    protected static ?int $sort = 30;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recently Added Clients')
            ->description('Clients added in the last 30 days')
            ->query(
                Client::query()
                    ->where('created_at', '>=', now()->subDays(30))
                    ->latest()
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Client $record) => $record->company ?? ''),
                TextColumn::make('email')
                    ->searchable()
                    ->icon('heroicon-m-envelope'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? ucfirst($state->value) : '')
                    ->sortable()
                    ->color(fn ($state) => $state ? match ($state->value) {
                        'lead'   => 'warning',
                        'active' => 'success',
                        'former' => 'gray',
                        default  => 'gray',
                    } : 'gray'),
                TextColumn::make('user.name')
                    ->label('Account Manager')
                    ->icon('heroicon-m-user'),
                TextColumn::make('created_at')
                    ->label('Added')
                    ->date()
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->url(fn (Client $record): string => route('filament.admin.resources.clients.view', $record))
                    ->icon('heroicon-m-eye'),
            ])
            ->paginated([5, 10, 25]);
    }
}
