<?php

// Get the current file content
$file_path = 'app/Filament/Resources/CategoryResource/RelationManagers/PostsRelationManager.php';
if (!file_exists($file_path)) {
    echo "File not found: $file_path\n";
    exit(1);
}

$content = file_get_contents($file_path);

// Add Auth facade import if it's not already there
if (strpos($content, 'use Illuminate\\Support\\Facades\\Auth;') === false) {
    $content = preg_replace(
        '/(namespace .*?;)\s+/s',
        "$1\n\nuse Illuminate\\Support\\Facades\\Auth;\n",
        $content
    );
}

// Fix the Auth::user()->id default value to use a closure with a null check
$content = preg_replace(
    '/->default\(Auth::user\(\)->id\)/',
    '->default(fn () => Auth::check() ? Auth::user()->id : null)',
    $content
);

file_put_contents($file_path, $content);
echo "Updated PostsRelationManager to properly handle Auth::user() default value.\n";
