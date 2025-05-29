<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\EmailPrioritiesWidget;
use App\Filament\Widgets\GitHubActivityWidget;
use App\Filament\Widgets\MorningBriefingWidget;
use App\Filament\Widgets\PerformanceMonitoringWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\StravaActivityWidget;
use App\Filament\Widgets\WeatherWidget;
use Carbon\Carbon;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Dashboard';

    public string $subheading = '';

    public function mount(): void
    {
        // Set dynamic subheading
        $this->subheading = now()->format('l, F j, Y');
        
        // Check if it's morning and add a greeting
        $hour = now()->hour;
        if ($hour < 12) {
            $this->subheading = 'Good morning! ' . $this->subheading;
        } elseif ($hour < 17) {
            $this->subheading = 'Good afternoon! ' . $this->subheading;
        } else {
            $this->subheading = 'Good evening! ' . $this->subheading;
        }
        
        // Add current time for real-time updates
        $this->subheading = now()->format('l, F j, Y');
    }

    /**
     * Get the header widgets for this dashboard page.
     * 
     * @return array<class-string>
     */
    protected function getHeaderWidgets(): array
    {
        return [
            MorningBriefingWidget::class,
        ];
    }

    /**
     * Get the main widgets for this dashboard page.
     * 
     * @return array<class-string>
     */
    protected function getWidgets(): array
    {
        return [
            EmailPrioritiesWidget::class,
            QuickActionsWidget::class,
            WeatherWidget::class,
            StravaActivityWidget::class,
            GitHubActivityWidget::class,
            PerformanceMonitoringWidget::class,
        ];
    }

    /**
     * Get the column configuration for header widgets.
     * 
     * @return int|array<string, int>
     */
    protected function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }

    /**
     * Get the column configuration for main widgets.
     * 
     * @return int|array<string, int>
     */
    protected function getWidgetsColumns(): int | array
    {
        return [
            'default' => 1,
            'sm'      => 2,
            'md'      => 2,
            'lg'      => 3,
        ];
    }

    /**
     * Get the polling interval for this dashboard.
     * 
     * @return string|array<string, string>|null
     */
    protected function getPollingInterval(): string | array | null
    {
        return '30s'; // Refresh every 30 seconds for real-time updates
    }

    /**
     * Determine if widgets should be discoverable.
     */
    protected function shouldDiscoverWidgets(): bool
    {
        return false; // We define widgets explicitly
    }

    /**
     * Get the maximum content width for the dashboard.
     */
    protected function getMaxContentWidth(): ?string
    {
        return 'full'; // Use full width for dashboard
    }
}
