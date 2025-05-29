<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CalendarPreviewWidget extends Widget
{
    protected static string $view = 'filament.widgets.calendar-preview';

    protected static ?int $sort = 3;

    public array $todaysEvents = [];
    public array $upcomingEvents = [];

    protected int | string | array $columnSpan = 1;

    public function mount(): void
    {
        // This would integrate with Google Calendar API
        // For now, using placeholder data
        $this->loadCalendarData();
    }

    protected function loadCalendarData(): void
    {
        // Placeholder events - would be replaced with actual calendar integration
        $this->todaysEvents = [
            [
                'title'    => 'Team Standup',
                'time'     => '9:00 AM',
                'duration' => '30 min',
                'type'     => 'meeting',
            ],
            [
                'title'    => 'Client Call - Project Review',
                'time'     => '2:00 PM',
                'duration' => '1 hour',
                'type'     => 'client',
            ],
        ];

        $this->upcomingEvents = [
            [
                'title' => 'Code Review',
                'date'  => 'Tomorrow',
                'time'  => '10:00 AM',
            ],
            [
                'title' => 'Weekly Planning',
                'date'  => 'Wednesday',
                'time'  => '3:00 PM',
            ],
        ];
    }
}
