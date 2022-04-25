<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestTest extends TestCase
{
    use RefreshDatabase;

    public function testTheApplicationReturnsASuccessfulResponse()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testTheGuestCantCreatePost()
    {
        $response = $this->get(route('post.create'));

        $response->assertStatus(302)
            ->assertRedirect('login');
    }

    public function testTheGuestCantEditPost()
    {
        $post = Post::factory()->create();

        $response = $this->get('/post/edit/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('login');
    }

    public function testTheGuestCantUpdatePost()
    {
        $post = Post::factory()->create();

        $response = $this->put('/post/update', [

        ]);

        $response->assertStatus(302)
            ->assertRedirect('login');
    }

    public function testTheGuestCantDeletePost()
    {
        $post = Post::factory()->create();

        $response = $this->get('/post/delete/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('login');
    }
}
