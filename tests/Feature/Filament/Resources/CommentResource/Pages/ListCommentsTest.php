<?php

namespace Tests\Feature\Filament\Resources\CommentResource\Pages;

use App\Filament\Resources\CommentResource;
use App\Filament\Resources\CommentResource\Pages\ListComments;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListCommentsTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_users_can_view_comments_listing()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(CommentResource::getUrl('index'));

        $response->assertSuccessful();
        $response->assertSee('Comments');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function unauthenticated_users_cannot_view_comments_listing()
    {
        $response = $this->get(CommentResource::getUrl('index'));

        $response->assertRedirect('/admin/login');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_displays_comments_in_the_table()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
        ]);

        $post = Post::factory()->create([
            'title' => 'Test Post',
        ]);

        $comment = Comment::factory()->create([
            'content' => 'This is a test comment',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(ListComments::class)
            ->assertSee('This is a test comment')
            ->assertSee('Test User')
            ->assertSee('Test Post');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_search_comments_by_content()
    {
        $user = User::factory()->create();

        Comment::factory()->create([
            'content' => 'First comment content',
        ]);

        Comment::factory()->create([
            'content' => 'Second comment content',
        ]);

        Livewire::actingAs($user)
            ->test(ListComments::class)
            ->searchTable('First')
            ->assertSee('First comment content')
            ->assertDontSee('Second comment content');
    }
}
