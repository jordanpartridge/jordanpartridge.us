<?php

namespace App\Filament\Pages;

use App\Filament\Resources\RideResource\Widgets\RecentRidesWidget;
use App\Filament\Resources\RideResource\Widgets\RidingStreakWidget;
use App\Filament\Widgets\RideGoalsWidget;
use Filament\Pages\Dashboard as BasePage;
use Filament\Widgets\AccountWidget;

class Dashboard extends BasePage
{
    public function getWidgets(): array
    {
        return [
            RecentRidesWidget::class,
            RidingStreakWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return [
            'default' => 1,
            'sm'      => 2,
            'class'   => 'items-start', // or items-stretch
        ];

    }
    protected function getHeaderWidgets(): array
    {
        return [
            RideGoalsWidget::class,

            AccountWidget::class,
        ];
    }
}
