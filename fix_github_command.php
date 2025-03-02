<?php

// Get the GitHub sync command content
$file_path = 'app/Console/Commands/SyncGitHubRepositories.php';
$content = file_get_contents($file_path);

// Update the message in the command
$content = str_replace(
    "No repositories to sync. Add repositories through the admin panel first.",
    "No active repositories found. The system will try to import repositories from GitHub.",
    $content
);

file_put_contents($file_path, $content);
echo "Updated SyncGitHubRepositories command to display more appropriate message.\n";
