<?php

namespace Tests\Feature\Filament\Resources\CommentResource\Pages;

use App\Filament\Resources\CommentResource;
use App\Filament\Resources\CommentResource\Pages\EditComment;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditCommentTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_users_can_view_edit_comment_form()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(CommentResource::getUrl('edit', ['record' => $comment]));

        $response->assertSuccessful();
        $response->assertSee('Edit Comment');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function unauthenticated_users_cannot_view_edit_comment_form()
    {
        $comment = Comment::factory()->create();

        $response = $this->get(CommentResource::getUrl('edit', ['record' => $comment]));

        $response->assertRedirect('/admin/login');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create();

        Livewire::actingAs($user)
            ->test(EditComment::class, ['record' => $comment->id])
            ->fillForm([
                'content' => 'This is the updated comment content',
                'post_id' => $post->id,
                'user_id' => $user->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('comments', [
            'id'      => $comment->id,
            'content' => 'This is the updated comment content',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_validates_required_fields_on_update()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        Livewire::actingAs($user)
            ->test(EditComment::class, ['record' => $comment->id])
            ->fillForm([
                'content' => '',
                'post_id' => null,
                'user_id' => null,
            ])
            ->call('save')
            ->assertHasFormErrors(['content', 'post_id', 'user_id']);
    }
}
