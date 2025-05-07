<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Contact Information')
                    ->description('Primary contact details')
                    ->schema([
                        TextEntry::make('email')
                            ->label('Email Address')
                            ->copyable()
                            ->icon('heroicon-m-envelope')
                            ->iconColor('primary')
                            ->extraAttributes(['class' => 'text-lg font-medium'])
                            ->columnSpan(2),
                        TextEntry::make('phone')
                            ->label('Phone Number')
                            ->copyable()
                            ->icon('heroicon-m-phone')
                            ->iconColor('success')
                            ->extraAttributes(['class' => 'text-lg font-medium'])
                            ->columnSpan(2),
                        TextEntry::make('website')
                            ->label('Website')
                            ->url(fn ($state) => $state)
                            ->openUrlInNewTab()
                            ->icon('heroicon-m-globe-alt')
                            ->columnSpan(2)
                            ->visible(fn ($state) => !empty($state)),
                    ])->columns(4),

                Section::make('Client Details')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Client Name')
                            ->extraAttributes(['class' => 'text-lg font-bold']),
                        TextEntry::make('company')
                            ->label('Company')
                            ->extraAttributes(['class' => 'text-lg']),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? ucfirst($state->value) : '')
                            ->color(fn ($state) => $state ? match ($state->value) {
                                'lead'   => 'warning',
                                'active' => 'success',
                                'former' => 'gray',
                                default  => 'gray',
                            } : 'gray'),
                        TextEntry::make('user.name')
                            ->label('Account Manager'),
                        TextEntry::make('last_contact_at')
                            ->label('Last Contact')
                            ->dateTime()
                            ->color(
                                fn ($state) => $state && $state->isPast() && $state->diffInDays(now()) > 30
                                    ? 'danger'
                                    : null
                            ),
                        TextEntry::make('created_at')
                            ->label('Client Since')
                            ->date(),
                    ])->columns(2),

                Section::make('Notes')
                    ->schema([
                        TextEntry::make('notes')
                            ->html()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('log_contact')
                ->icon('heroicon-o-phone')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Log contact with client')
                ->modalDescription(fn () => "Record new contact with {$this->record->name}")
                ->action(function (): void {
                    $this->record->update(['last_contact_at' => now()]);
                    $this->notify('success', 'Contact logged successfully');
                }),
        ];
    }
}
