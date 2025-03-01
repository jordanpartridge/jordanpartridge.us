<?php

namespace Tests\Feature\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class EditPostTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_users_can_view_edit_post_form()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(PostResource::getUrl('edit', ['record' => $post]));

        $response->assertSuccessful();
        $response->assertSee('Edit Post');
        $response->assertSee($post->title);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function unauthenticated_users_cannot_view_edit_post_form()
    {
        $post = Post::factory()->create();

        $response = $this->get(PostResource::getUrl('edit', ['record' => $post]));

        $response->assertRedirect('/admin/login');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_post()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $image = UploadedFile::fake()->image('new-image.jpg');

        Livewire::actingAs($user)
            ->test(EditPost::class, ['record' => $post->id])
            ->fillForm([
                'title'   => 'Updated Post Title',
                'body'    => 'This is the updated content of the post.',
                'status'  => 'published',
                'image'   => $image,
                'user_id' => $user->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('posts', [
            'id'      => $post->id,
            'title'   => 'Updated Post Title',
            'body'    => 'This is the updated content of the post.',
            'status'  => 'published',
            'user_id' => $user->id,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_validates_required_fields_on_update()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        Livewire::actingAs($user)
            ->test(EditPost::class, ['record' => $post->id])
            ->fillForm([
                'title'  => '',
                'body'   => '',
                'status' => '',
            ])
            ->call('save')
            ->assertHasFormErrors(['title', 'body', 'status']);
    }
}
