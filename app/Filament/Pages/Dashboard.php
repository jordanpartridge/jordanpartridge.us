<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\EmailPrioritiesWidget;
use App\Filament\Widgets\FitnessTrackerWidget;
use App\Filament\Widgets\TodaysFocusWidget;
use App\Filament\Widgets\QuickStatsWidget;
use App\Filament\Widgets\MorningBriefingWidget;
use App\Filament\Widgets\CalendarPreviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Morning Command Center';

    protected static ?string $navigationIcon = 'heroicon-o-sun';

    protected ?string $heading = 'Good Morning, Jordan';

    protected ?string $subheading = 'Here\'s your day at a glance';

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'sm'      => 2,
            'md'      => 2,
            'lg'      => 3,
            'xl'      => 4,
        ];
    }

    public function mount(): void
    {
        parent::mount();

        // Update greeting based on time of day
        $hour = now()->hour;
        $this->heading = match(true) {
            $hour < 12 => 'Good Morning, Jordan',
            $hour < 17 => 'Good Afternoon, Jordan',
            default    => 'Good Evening, Jordan',
        };

        $this->subheading = now()->format('l, F j, Y');
    }

    public function getHeaderWidgets(): array
    {
        return [
            MorningBriefingWidget::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            EmailPrioritiesWidget::class,
            TodaysFocusWidget::class,
            CalendarPreviewWidget::class,
            FitnessTrackerWidget::class,
            QuickStatsWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }

    public function getWidgetsColumns(): int | array
    {
        return [
            'default' => 1,
            'sm'      => 2,
            'lg'      => 3,
            'xl'      => 4,
        ];
    }
}
