<?php

namespace Tests\Feature\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListPostsTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_users_can_view_posts_listing()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(PostResource::getUrl('index'));

        $response->assertSuccessful();
        $response->assertSee('Posts');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function unauthenticated_users_cannot_view_posts_listing()
    {
        $response = $this->get(PostResource::getUrl('index'));

        $response->assertRedirect('/admin/login');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_displays_posts_in_the_table()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'title' => 'Test Post Title',
        ]);

        Livewire::actingAs($user)
            ->test(ListPosts::class)
            ->assertSee('Test Post Title');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_filter_posts_by_status()
    {
        $user = User::factory()->create();

        Post::factory()->create([
            'title'  => 'Published Post',
            'status' => 'published',
        ]);

        Post::factory()->create([
            'title'  => 'Draft Post',
            'status' => 'draft',
        ]);

        Livewire::actingAs($user)
            ->test(ListPosts::class)
            ->filterTable('status', 'published')
            ->assertSee('Published Post')
            ->assertDontSee('Draft Post');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_search_posts_by_title()
    {
        $user = User::factory()->create();

        Post::factory()->create([
            'title' => 'First Post',
        ]);

        Post::factory()->create([
            'title' => 'Second Post',
        ]);

        Livewire::actingAs($user)
            ->test(ListPosts::class)
            ->searchTable('First')
            ->assertSee('First Post')
            ->assertDontSee('Second Post');
    }
}
