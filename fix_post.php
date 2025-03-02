<?php

$content = file_get_contents('app/Models/Post.php');
$fixed_content = preg_replace('/\\}\\n\\n\\n\\n    public function scopePublished/', '}\\n\\n    public function scopePublished', $content);
file_put_contents('app/Models/Post.php', $fixed_content);
