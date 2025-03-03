<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_post()
    {
        $user = User::factory()->create();

        $post = Post::create([
            'title'   => 'Test Post',
            'body'    => 'This is the test post body',
            'status'  => 'draft',
            'type'    => 'post',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('posts', [
            'id'      => $post->id,
            'title'   => 'Test Post',
            'body'    => 'This is the test post body',
            'status'  => 'draft',
            'type'    => 'post',
            'user_id' => $user->id,
        ]);
    }
}
