<?php

// Get the current Post model content
$post_content = file_get_contents('app/Models/Post.php');

// Fix the excludeFeatured scope to make $featured parameter optional
$post_content = str_replace(
    "public function scopeExcludeFeatured(\$query, \$featured): mixed",
    "public function scopeExcludeFeatured(\$query, \$featured = null): mixed",
    $post_content
);

// Add a null check to the excludeFeatured scope
$post_content = str_replace(
    "return \$query->where('id', '!=', \$featured);",
    "return \$featured ? \$query->where('id', '!=', \$featured) : \$query;",
    $post_content
);

file_put_contents('app/Models/Post.php', $post_content);
echo "Fixed the excludeFeatured scope to handle optional parameter.\n";
