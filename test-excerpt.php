<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Post;
use App\Models\User;

// Get first user
$user = User::first();

// Create a test post without an excerpt
$post = new Post([
    'title'   => 'Test Auto Excerpt Generation',
    'body'    => '<p>This is a test post with <strong>formatted</strong> content to test the automatic excerpt generation. It should remove HTML tags and create a clean excerpt of the first 200 characters. Let\'s make sure it works properly with our new PostObserver implementation.</p><p>This paragraph should not be included in the excerpt.</p>',
    'type'    => 'post',
    'status'  => 'published',
    'user_id' => $user->id,
]);

$post->save();

echo "Post created with ID: " . $post->id . PHP_EOL;
echo "Generated excerpt: " . $post->excerpt . PHP_EOL;
