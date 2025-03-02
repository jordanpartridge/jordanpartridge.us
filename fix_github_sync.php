<?php

// Get the GitHub sync service content
$file_path = 'app/Services/GitHub/GitHubSyncService.php';
$content = file_get_contents($file_path);

// Check if we need to modify the syncAllRepositories method
if (strpos($content, 'throw new \\Exception(\'No active repositories found') !== false) {
    // This is the part we want to replace
    $pattern = '/\$repositories = GithubRepository::where\(\'is_active\', true\)->get\(\);
        \$success = collect\(\);

        if \(\$repositories->isEmpty\(\)\) \{
            throw new \\\\Exception\(\'No active repositories found\. Please add repositories through the admin panel first\.\'\);
        \}/s';

    // New implementation that auto-imports repos
    $replacement = '$repositories = GithubRepository::where(\'is_active\', true)->get();
        $success = collect();

        // If no repositories in the database, fetch from GitHub and create them
        if ($repositories->isEmpty()) {
            // Get the username from settings
            $username = $this->settings->username;

            // Create a GitHub client
            $client = new \App\Services\GitHub\GitHubClient($this->settings->getToken());

            // Fetch repositories from GitHub
            $githubRepos = $client->getUserRepositories($username);

            // Create repositories in the database
            foreach ($githubRepos as $repo) {
                // Skip forks unless you want to include them
                if ($repo[\'fork\'] ?? false) {
                    continue;
                }

                // Create basic repository record
                $newRepo = GithubRepository::create([
                    \'name\' => $repo[\'name\'],
                    \'repository\' => $repo[\'name\'],
                    \'description\' => $repo[\'description\'] ?? null,
                    \'url\' => $repo[\'html_url\'],
                    \'technologies\' => [$repo[\'language\']],
                    \'stars_count\' => $repo[\'stargazers_count\'] ?? 0,
                    \'forks_count\' => $repo[\'forks_count\'] ?? 0,
                    \'featured\' => false,
                    \'is_active\' => true,
                    \'display_order\' => 0,
                    \'last_fetched_at\' => now(),
                ]);

                // Update repositories collection with the new ones
                $repositories->push($newRepo);
            }
        }';

    // Replace the content
    $content = preg_replace($pattern, $replacement, $content);

    // Save the updated file
    file_put_contents($file_path, $content);
    echo "Updated GitHubSyncService to auto-import repositories when none exist.\n";
} else {
    echo "The syncAllRepositories method doesn't have the expected pattern or has already been modified.\n";

    // Let's check if we have a different pattern
    if (strpos($content, 'if ($repositories->isEmpty())') !== false) {
        echo "Found the isEmpty check, but in a different format. Manually examining...\n";

        // Output the relevant portion for manual analysis
        preg_match('/public function syncAllRepositories\(\).*?{(.*?)}/s', $content, $matches);
        if (!empty($matches[1])) {
            echo "Current syncAllRepositories method:\n" . $matches[1] . "\n";
        }
    }
}
