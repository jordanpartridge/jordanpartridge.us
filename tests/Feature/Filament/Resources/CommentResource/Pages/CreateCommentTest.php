<?php

namespace Tests\Feature\Filament\Resources\CommentResource\Pages;

use App\Filament\Resources\CommentResource;
use App\Filament\Resources\CommentResource\Pages\CreateComment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_users_can_view_create_comment_form()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(CommentResource::getUrl('create'));

        $response->assertSuccessful();
        $response->assertSee('Create Comment');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function unauthenticated_users_cannot_view_create_comment_form()
    {
        $response = $this->get(CommentResource::getUrl('create'));

        $response->assertRedirect('/admin/login');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateComment::class)
            ->fillForm([
                'content' => 'This is a new test comment',
                'post_id' => $post->id,
                'user_id' => $user->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a new test comment',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_validates_required_fields()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateComment::class)
            ->fillForm([
                'content' => '',
                'post_id' => null,
                'user_id' => null,
            ])
            ->call('create')
            ->assertHasFormErrors(['content', 'post_id', 'user_id']);
    }
}
