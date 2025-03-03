<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_belongs_to_a_post()
    {
        $comment = Comment::factory()->create();

        $this->assertInstanceOf(Post::class, $comment->post);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_belongs_to_a_user()
    {
        $comment = Comment::factory()->create();

        $this->assertInstanceOf(User::class, $comment->user);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_be_created_with_proper_attributes()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $comment = Comment::factory()->create([
            'content' => 'This is a test comment',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $this->assertEquals('This is a test comment', $comment->content);
        $this->assertEquals($post->id, $comment->post_id);
        $this->assertEquals($user->id, $comment->user_id);
    }
}
