<?php

$content = file_get_contents('app/Models/Post.php');

// Replace the custom HasUuids trait import with Laravel's built-in version
$updated_content = str_replace(
    "use App\\Models\\Traits\\HasUuids;",
    "use Illuminate\\Database\\Eloquent\\Concerns\\HasUuids;",
    $content
);

file_put_contents('app/Models/Post.php', $updated_content);
echo "Updated Post.php to use Laravel's built-in HasUuids trait instead of the custom one.\n";
