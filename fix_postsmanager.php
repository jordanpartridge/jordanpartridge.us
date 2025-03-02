<?php

$content = file_get_contents('app/Filament/Resources/CategoryResource/RelationManagers/PostsRelationManager.php');

// Check if Auth is imported
if (strpos($content, 'use Illuminate\\Support\\Facades\\Auth;') === false) {
    // Add the import
    $content = preg_replace(
        '/(namespace .*?;)/s',
        "$1\n\nuse Illuminate\\Support\\Facades\\Auth;",
        $content
    );
}

// Replace the default value
$content = str_replace(
    "->default(Auth::user()->id)",
    "->default(fn () => Auth::check() ? Auth::user()->id : null)",
    $content
);

file_put_contents('app/Filament/Resources/CategoryResource/RelationManagers/PostsRelationManager.php', $content);
echo "Updated Auth::user()->id default value with safer version\n";
