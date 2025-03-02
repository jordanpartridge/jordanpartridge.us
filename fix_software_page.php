<?php

$content = file_get_contents('resources/views/pages/software-development/index.blade.php');

// Replace arsenal with tech-stack
if (strpos($content, '<x-software-development.arsenal/>') !== false) {
    $content = str_replace(
        '<x-software-development.arsenal/>',
        '<x-software-development.tech-stack/>',
        $content
    );
    file_put_contents('resources/views/pages/software-development/index.blade.php', $content);
    echo "Replaced arsenal component with tech-stack component\n";
} else {
    echo "Arsenal component reference not found\n";
}
