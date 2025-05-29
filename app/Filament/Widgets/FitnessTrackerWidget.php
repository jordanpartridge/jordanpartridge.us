<?php

namespace App\Filament\Widgets;

use App\Models\Ride;
use Filament\Widgets\Widget;
use Carbon\Carbon;

class FitnessTrackerWidget extends Widget
{
    protected static string $view = 'filament.widgets.fitness-tracker';

    protected static ?int $sort = 4;

    public ?Ride $lastRide = null;
    public ?string $timeSinceLastRide = null;
    public array $weeklyStats = [];
    public array $monthlyProgress = [];
    public ?int $currentStreak = null;
    public ?float $weeklyGoalProgress = null;

    protected int | string | array $columnSpan = [
        'default' => 1,
        'lg'      => 2,
    ];

    public function mount(): void
    {
        $this->loadFitnessData();
    }

    public function getRideStatus(): array
    {
        if (!$this->lastRide) {
            return [
                'color'   => 'danger',
                'message' => 'No rides tracked yet',
                'icon'    => 'heroicon-o-exclamation-circle',
            ];
        }

        $daysSinceRide = Carbon::parse($this->lastRide->start_date)->diffInDays(now());

        return match(true) {
            $daysSinceRide === 0 => [
                'color'   => 'success',
                'message' => 'Great job! You rode today',
                'icon'    => 'heroicon-o-check-circle',
            ],
            $daysSinceRide === 1 => [
                'color'   => 'success',
                'message' => 'Nice ride yesterday',
                'icon'    => 'heroicon-o-check-circle',
            ],
            $daysSinceRide <= 3 => [
                'color'   => 'warning',
                'message' => 'Time to get back on the bike',
                'icon'    => 'heroicon-o-clock',
            ],
            default => [
                'color'   => 'danger',
                'message' => 'Your bike misses you!',
                'icon'    => 'heroicon-o-exclamation-triangle',
            ],
        };
    }

    protected function loadFitnessData(): void
    {
        // Get last ride
        $this->lastRide = Ride::orderBy('date', 'desc')
            ->first();

        if ($this->lastRide) {
            $this->timeSinceLastRide = $this->getTimeSinceLastRide();
        }

        // Calculate weekly stats
        $this->calculateWeeklyStats();

        // Calculate current streak
        $this->calculateStreak();

        // Calculate monthly progress
        $this->calculateMonthlyProgress();
    }

    protected function getTimeSinceLastRide(): string
    {
        if (!$this->lastRide) {
            return 'No rides yet';
        }

        $lastRideDate = Carbon::parse($this->lastRide->start_date);
        $now = now();

        $diffInDays = $lastRideDate->diffInDays($now);
        $diffInHours = $lastRideDate->diffInHours($now);

        if ($diffInHours < 24) {
            return $diffInHours . ' hours ago';
        } elseif ($diffInDays == 1) {
            return 'Yesterday';
        } elseif ($diffInDays < 7) {
            return $diffInDays . ' days ago';
        } else {
            return $lastRideDate->format('M j');
        }
    }

    protected function calculateWeeklyStats(): void
    {
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $weeklyRides = Ride::query()
            ->whereBetween('date', [$weekStart, $weekEnd])
            ->get();

        $this->weeklyStats = [
            'rides'     => $weeklyRides->count(),
            'distance'  => round($weeklyRides->sum('distance') * 0.000621371, 1), // meters to miles
            'time'      => $this->formatDuration($weeklyRides->sum('moving_time')),
            'elevation' => round($weeklyRides->sum('total_elevation_gain') * 3.28084), // meters to feet
        ];

        // Weekly goal progress (assuming 20 miles/week goal)
        $weeklyGoal = 20;
        $this->weeklyGoalProgress = min(100, round(($this->weeklyStats['distance'] / $weeklyGoal) * 100));
    }

    protected function calculateStreak(): void
    {
        $dates = Ride::query()
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->unique()
            ->values();

        $streak = 0;
        $checkDate = now()->format('Y-m-d');

        foreach ($dates as $date) {
            if ($date == $checkDate || $date == Carbon::parse($checkDate)->subDay()->format('Y-m-d')) {
                $streak++;
                $checkDate = Carbon::parse($date)->subDay()->format('Y-m-d');
            } else {
                break;
            }
        }

        $this->currentStreak = $streak;
    }

    protected function calculateMonthlyProgress(): void
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $monthlyRides = Ride::query()
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();

        $totalDays = now()->daysInMonth;
        $daysPassed = now()->day;

        $this->monthlyProgress = [
            'rides'     => $monthlyRides->count(),
            'distance'  => round($monthlyRides->sum('distance') * 0.000621371, 1),
            'pace'      => $daysPassed > 0 ? round($monthlyRides->count() / $daysPassed, 1) : 0,
            'projected' => round(($monthlyRides->count() / $daysPassed) * $totalDays),
        ];
    }

    protected function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $minutes);
        }

        return sprintf('%dm', $minutes);
    }
}
