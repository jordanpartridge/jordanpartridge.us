<?php

// Get the current Post model content
$post_content = file_get_contents('app/Models/Post.php');

// Remove the HasUuids import
$post_content = preg_replace('/use Illuminate\\\\Database\\\\Eloquent\\\\Concerns\\\\HasUuids;\n/', '', $post_content);

// Remove the HasUuids trait from the use statement
$post_content = str_replace(
    'use HasFactory, HasUuids, HasSlug;',
    'use HasFactory, HasSlug;',
    $post_content
);

// Remove 'uuid' from $fillable if it exists
$post_content = preg_replace('/\'uuid\',\n\s+/', '', $post_content);

file_put_contents('app/Models/Post.php', $post_content);
echo "Updated Post.php to remove UUID support and revert to regular IDs.\n";
