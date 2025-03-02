<?php

// Check if the tech-stack component already exists
if (file_exists('resources/views/components/software-development/tech-stack.blade.php')) {
    echo "tech-stack.blade.php already exists.\n";
} else {
    // Check if the arsenal component exists
    if (file_exists('resources/views/components/software-development/arsenal.blade.php')) {
        // Read the arsenal component content
        $arsenal_content = file_get_contents('resources/views/components/software-development/arsenal.blade.php');

        // Create the tech-stack component with the same content
        file_put_contents('resources/views/components/software-development/tech-stack.blade.php', $arsenal_content);

        echo "Created tech-stack.blade.php with arsenal.blade.php content.\n";
    } else {
        echo "Neither arsenal.blade.php nor tech-stack.blade.php exists.\n";
    }
}

// Now update the references in index.blade.php
$index_content = file_get_contents('resources/views/pages/software-development/index.blade.php');

// Check if arsenal is referenced and replace with tech-stack
if (strpos($index_content, '<x-software-development.arsenal/>') !== false) {
    $index_content = str_replace('<x-software-development.arsenal/>', '<x-software-development.tech-stack/>', $index_content);
    file_put_contents('resources/views/pages/software-development/index.blade.php', $index_content);
    echo "Updated index.blade.php to use tech-stack component.\n";
} else {
    echo "No reference to arsenal component found in index.blade.php.\n";
}
