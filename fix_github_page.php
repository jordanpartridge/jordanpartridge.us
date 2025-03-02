<?php

// Get the GitHub list page content
$file_path = 'app/Filament/Resources/GithubRepositoryResource/Pages/ListGithubRepositories.php';
$content = file_get_contents($file_path);

// Update the message in the notification when no repositories are found
$content = str_replace(
    "->body('Add repositories through the Create button first')",
    "->body('Syncing will automatically import your GitHub repositories. Please try again.')",
    $content
);

file_put_contents($file_path, $content);
echo "Updated ListGithubRepositories page with a better user message.\n";
