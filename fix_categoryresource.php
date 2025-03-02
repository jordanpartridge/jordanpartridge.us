<?php

$content = file_get_contents('app/Filament/Resources/CategoryResource.php');
// Check if the import for ListCategories is missing
if (strpos($content, 'use App\\Filament\\Resources\\CategoryResource\\Pages\\ListCategories;') === false &&
    strpos($content, 'Pages\\ListCategories::route') !== false) {
    // Add the import
    $content = str_replace(
        'use App\\Filament\\Resources\\CategoryResource\\Pages;',
        "use App\\Filament\\Resources\\CategoryResource\\Pages;\nuse App\\Filament\\Resources\\CategoryResource\\Pages\\ListCategories;",
        $content
    );
    file_put_contents('app/Filament/Resources/CategoryResource.php', $content);
    echo "Added missing import for ListCategories\n";
} else {
    echo "Import for ListCategories already exists or no reference found\n";
}
