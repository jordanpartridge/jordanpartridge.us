<?php

namespace Tests\Feature\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_users_can_view_create_post_form()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(PostResource::getUrl('create'));

        $response->assertSuccessful();
        $response->assertSee('Create Post');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function unauthenticated_users_cannot_view_create_post_form()
    {
        $response = $this->get(PostResource::getUrl('create'));

        $response->assertRedirect('/admin/login');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_post()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $image = UploadedFile::fake()->image('test-image.jpg');

        Livewire::actingAs($user)
            ->test(CreatePost::class)
            ->fillForm([
                'title'   => 'New Test Post',
                'body'    => 'This is the content of the test post.',
                'status'  => 'draft',
                'image'   => $image,
                'user_id' => $user->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('posts', [
            'title'   => 'New Test Post',
            'body'    => 'This is the content of the test post.',
            'status'  => 'draft',
            'user_id' => $user->id,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_validates_required_fields()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(CreatePost::class)
            ->fillForm([
                'title'  => '',
                'body'   => '',
                'status' => '',
                'image'  => null,
            ])
            ->call('create')
            ->assertHasFormErrors(['title', 'body', 'status', 'image']);
    }
}
