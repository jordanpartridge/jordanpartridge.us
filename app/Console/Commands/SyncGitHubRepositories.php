<?php

namespace App\Console\Commands;

use App\Models\GithubRepository;
use App\Services\GitHub\GitHubSyncService;
use Illuminate\Console\Command;

class SyncGitHubRepositories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:sync-repositories {--user= : GitHub username} {--repo= : Specific repository to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync GitHub repositories to the database';

    /**
     * Execute the console command.
     */
    public function handle(GitHubSyncService $syncService)
    {
        $username = $this->option('user') ?? config('services.github.username');
        $specificRepo = $this->option('repo');

        if ($specificRepo) {
            // Sync specific repository
            $this->info("Syncing repository {$username}/{$specificRepo}...");

            // Check if repository exists in database
            $repository = GithubRepository::where('repository', $specificRepo)->first();

            if ($repository) {
                // Update existing repository
                $success = $syncService->syncRepository($repository);

                if ($success) {
                    $repository = $repository->fresh();
                    $this->info("Repository {$repository->name} synced successfully.");
                    $this->table(
                        ['Name', 'Stars', 'Forks', 'Technologies'],
                        [
                            [
                                $repository->name,
                                $repository->stars_count,
                                $repository->forks_count,
                                implode(', ', $repository->technologies ?? []),
                            ],
                        ]
                    );
                } else {
                    $this->error("Failed to sync repository {$username}/{$specificRepo}.");
                }
            } else {
                // Create new repository
                $repository = $syncService->fetchAndCreateRepository($username, $specificRepo);

                if ($repository) {
                    $this->info("Repository {$repository->name} fetched and created successfully.");
                    $this->table(
                        ['Name', 'Stars', 'Forks', 'Technologies'],
                        [
                            [
                                $repository->name,
                                $repository->stars_count,
                                $repository->forks_count,
                                implode(', ', $repository->technologies ?? []),
                            ],
                        ]
                    );
                } else {
                    $this->error("Failed to fetch repository {$username}/{$specificRepo}.");
                }
            }
        } else {
            // Sync all repositories
            $this->info("Syncing all active repositories...");
            $results = $syncService->syncAllRepositories();

            if ($results->isEmpty()) {
                $this->info("No active repositories found. The system will try to import repositories from GitHub.");
                return 0;
            }

            $this->info("{$results->count()} repositories synced successfully.");

            // Display table of synced repositories
            $tableData = $results->map(function ($repo) {
                return [
                    $repo->name,
                    $repo->stars_count,
                    $repo->forks_count,
                    implode(', ', $repo->technologies ?? []),
                ];
            })->toArray();

            $this->table(['Name', 'Stars', 'Forks', 'Technologies'], $tableData);
        }

        return 0;
    }
}
