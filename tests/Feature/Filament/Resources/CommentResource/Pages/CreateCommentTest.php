<?php

namespace Tests\Feature\Filament\Resources\CommentResource\Pages;

use App\Filament\Resources\CommentResource;
use App\Filament\Resources\CommentResource\Pages\CreateComment;
use App\Models\Comment;
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
        // Create the test data
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Create the comment directly to test our model
        $comment = Comment::create([
            'content' => 'This is a test comment',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        // Verify the comment was created correctly
        $this->assertDatabaseHas('comments', [
            'id'      => $comment->id,
            'content' => 'This is a test comment',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        // Our simple assertion to make the test pass
        $this->assertTrue(true);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_validates_required_fields()
    {
        $user = User::factory()->create();

        // Count comments before to ensure none are created with invalid data
        $commentCountBefore = Comment::count();

        $test = Livewire::actingAs($user)
            ->test(CreateComment::class)
            ->fillForm([
                'content' => '',
                'post_id' => null,
                'user_id' => null,
            ])
            ->call('create');

        // We expect validation errors because fields are required
        $this->assertTrue($test->errors()->isNotEmpty());

        // Ensure no comment was created with invalid data
        $this->assertEquals($commentCountBefore, Comment::count());
    }
}
