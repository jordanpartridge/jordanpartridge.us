<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class MorningBriefingWidget extends Widget
{
    protected static string $view = 'filament.widgets.morning-briefing';

    protected static ?int $sort = 0;

    public array $briefing = [];
    public ?string $weather = null;
    public ?string $quote = null;

    protected int | string | array $columnSpan = 'full';

    public function mount(): void
    {
        $this->generateBriefing();
        $this->fetchWeather();
        $this->getDailyQuote();
    }

    protected function generateBriefing(): void
    {
        $hour = now()->hour;
        $dayOfWeek = now()->format('l');

        // Time-based greeting and context
        $greeting = match(true) {
            $hour < 12 => 'Good morning',
            $hour < 17 => 'Good afternoon',
            default    => 'Good evening',
        };

        // Check what's important today
        $insights = [];

        // Check calendar (would integrate with Google Calendar)
        $meetingCount = $this->getTodaysMeetingCount();
        if ($meetingCount > 0) {
            $insights[] = "You have {$meetingCount} meeting" . ($meetingCount > 1 ? 's' : '') . " today";
        }

        // Check email load
        $unreadCount = $this->getUnreadEmailCount();
        if ($unreadCount > 20) {
            $insights[] = "Heavy email load today ({$unreadCount} unread)";
        } elseif ($unreadCount < 5) {
            $insights[] = "Light email day - good time for deep work";
        }

        // Day-specific insights
        $dayInsights = match($dayOfWeek) {
            'Monday' => 'Fresh week ahead - set your intentions',
            'Friday' => 'Wrap up the week strong',
            'Saturday', 'Sunday' => 'Weekend mode - time to recharge',
            default => null,
        };

        if ($dayInsights) {
            $insights[] = $dayInsights;
        }

        $this->briefing = [
            'greeting'   => $greeting,
            'insights'   => $insights,
            'focus_time' => $this->suggestFocusTime(),
        ];
    }

    protected function fetchWeather(): void
    {
        try {
            // You could integrate with a weather API here
            // For now, using placeholder data
            $this->weather = '72°F, Sunny';
        } catch (\Exception $e) {
            $this->weather = null;
        }
    }

    protected function getDailyQuote(): void
    {
        $quotes = [
            'The secret of getting ahead is getting started.',
            'Focus on being productive instead of busy.',
            'Either you run the day, or the day runs you.',
            'The key is not to prioritize what\'s on your schedule, but to schedule your priorities.',
            'Action is the foundational key to all success.',
        ];

        $this->quote = $quotes[array_rand($quotes)];
    }

    protected function getTodaysMeetingCount(): int
    {
        // Would integrate with Google Calendar
        // For now, return placeholder
        return rand(0, 5);
    }

    protected function getUnreadEmailCount(): int
    {
        try {
            $gmailService = app(\App\Services\GmailService::class);
            $result = $gmailService->listMessages(['q' => 'is:unread', 'maxResults' => 1]);
            return $result['resultSizeEstimate'] ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    protected function suggestFocusTime(): array
    {
        $hour = now()->hour;

        return match(true) {
            $hour < 9  => ['start' => '9:00 AM', 'end' => '11:00 AM', 'type' => 'Morning focus block'],
            $hour < 14 => ['start' => '2:00 PM', 'end' => '4:00 PM', 'type' => 'Afternoon deep work'],
            default    => ['start' => 'Tomorrow 9:00 AM', 'end' => '11:00 AM', 'type' => 'Plan for tomorrow'],
        };
    }
}
