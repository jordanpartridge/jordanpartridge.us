<?php

// Get the CommentsRelationManager content
$file_path = 'app/Filament/Resources/PostResource/RelationManagers/CommentsRelationManager.php';

// Check if the file exists
if (!file_exists($file_path)) {
    echo "File not found: $file_path\n";
    exit(1);
}

$content = file_get_contents($file_path);

// Check if we need to add the Auth import
if (strpos($content, 'use Illuminate\\Support\\Facades\\Auth;') === false) {
    $content = preg_replace(
        '/(namespace .*?;)/s',
        "$1\n\nuse Illuminate\\Support\\Facades\\Auth;",
        $content
    );
}

// Update the default value to include a null check
$content = str_replace(
    "->default(Auth::user()->id)",
    "->default(fn () => Auth::check() ? Auth::user()->id : null)",
    $content
);

file_put_contents($file_path, $content);
echo "Updated CommentsRelationManager to properly handle Auth::user() default value.\n";
