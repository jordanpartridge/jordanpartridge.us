<?php

// Get the current Post model content
$post_content = file_get_contents('app/Models/Post.php');

// Fix the excludeFeatured scope to properly exclude featured posts
$post_content = str_replace(
    "public function scopeExcludeFeatured(\$query, \$featured = null): mixed\n    {\n        return \$featured ? \$query->where('id', '!=', \$featured) : \$query;\n    }",
    "public function scopeExcludeFeatured(\$query, \$featured = null): mixed\n    {\n        return \$query->where('featured', false);\n    }",
    $post_content
);

// Add the list scope implementation
// First check if there's already a list scope
if (strpos($post_content, 'paginate(12)') !== false) {
    // Fix the existing list scope to properly handle the featured posts
    $post_content = str_replace(
        "public function scopeList(\$query): mixed\n    {\n        return \$query->orderBy('created_at', 'DESC')\n            ->paginate(12);\n    }",
        "public function scopeList(\$query): mixed\n    {\n        return \$query->published()\n            ->typePost()\n            ->excludeFeatured()\n            ->orderBy('created_at', 'DESC');\n    }",
        $post_content
    );
} else {
    // Add a new list scope implementation
    $post_content = str_replace(
        "public function scopePublished(\$query): void\n    {\n        \$query->where('status', Post::STATUS_PUBLISHED);\n    }",
        "public function scopePublished(\$query): void\n    {\n        \$query->where('status', Post::STATUS_PUBLISHED);\n    }\n\n    public function scopeList(\$query): mixed\n    {\n        return \$query->published()\n            ->typePost()\n            ->excludeFeatured()\n            ->orderBy('created_at', 'DESC');\n    }",
        $post_content
    );
}

file_put_contents('app/Models/Post.php', $post_content);
echo "Fixed the excludeFeatured and list scopes in the Post model.\n";
