<?php

// Get the PostResource content
$file_path = 'app/Filament/Resources/PostResource.php';

// Check if the file exists
if (!file_exists($file_path)) {
    echo "File not found: $file_path\n";
    exit(1);
}

$content = file_get_contents($file_path);

// Update the default value to include a null check
$content = str_replace(
    "->default(Auth::user()->id)",
    "->default(fn () => Auth::check() ? Auth::user()->id : null)",
    $content
);

file_put_contents($file_path, $content);
echo "Updated PostResource to properly handle Auth::user() default value.\n";
