<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Post;
use App\Models\GithubRepository;

class QuickStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = [
        'default' => 1,
        'lg'      => 2,
    ];

    protected function getStats(): array
    {
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $githubRepos = GithubRepository::count();
        $lastPostDate = Post::latest('created_at')->first()?->created_at;

        return [
            Stat::make('Published Posts', $publishedPosts . '/' . $totalPosts)
                ->description($lastPostDate ? 'Last: ' . $lastPostDate->diffForHumans() : 'No posts yet')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('GitHub Repos', $githubRepos)
                ->description('Connected repositories')
                ->descriptionIcon('heroicon-m-code-bracket')
                ->color('info'),

            Stat::make('Week Activity', $this->getWeekActivity())
                ->description('Actions this week')
                ->descriptionIcon('heroicon-m-fire')
                ->color('warning'),
        ];
    }

    protected function getWeekActivity(): int
    {
        // Count various activities from the past week
        $posts = Post::where('updated_at', '>', now()->subWeek())->count();
        $repos = GithubRepository::where('updated_at', '>', now()->subWeek())->count();

        return $posts + $repos;
    }
}
