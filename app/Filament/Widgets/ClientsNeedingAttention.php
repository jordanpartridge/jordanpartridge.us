<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ClientsNeedingAttention extends BaseWidget
{
    protected static ?int $sort = 25;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Clients Needing Attention')
            ->description('Active clients with no recent contact')
            ->query(
                Client::query()
                    ->where('status', 'active')
                    ->where(function ($query) {
                        $query->whereNull('last_contact_at')
                            ->orWhere('last_contact_at', '<=', now()->subDays(30));
                    })
                    ->orderBy('last_contact_at')
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Client $record) => $record->company ?? ''),
                TextColumn::make('user.name')
                    ->label('Account Manager')
                    ->icon('heroicon-m-user'),
                TextColumn::make('last_contact_at')
                    ->label('Last Contact')
                    ->date()
                    ->sortable()
                    ->default('Never')
                    ->description(fn (Client $record) => $record->last_contact_at
                        ? 'Days since contact: ' . $record->last_contact_at->diffInDays()
                        : 'No contact recorded'),
                TextColumn::make('email')
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('phone')
                    ->searchable()
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->copyMessage('Phone number copied')
                    ->copyMessageDuration(1500),
            ])
            ->actions([
                Action::make('view')
                    ->url(fn (Client $record): string => route('filament.admin.resources.clients.view', $record))
                    ->icon('heroicon-m-eye'),
                Action::make('log_contact')
                    ->label('Log Contact')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->url(fn (Client $record): string => route('clients.log-contact', $record))
                    ->openUrlInNewTab()
                    ->extraAttributes([
                        'hx-post'    => fn (Client $record): string => route('clients.log-contact', $record),
                        'hx-swap'    => 'none',
                        'hx-trigger' => 'click',
                        'onclick'    => 'event.preventDefault(); this.closest("form").submit(); setTimeout(() => window.location.reload(), 500);',
                    ]),
            ])
            ->paginated([5, 10, 25]);
    }
}
