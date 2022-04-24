<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_the_guest_cant_create_post()
    {
        $response = $this->get(route('post.create'));

        $response->assertStatus(302)
            ->assertRedirect('login');
    }

    public function test_the_guest_cant_edit_post()
    {
        $post = Post::factory()->create();

        $response = $this->get('/post/edit/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('login');
    }

    public function test_the_guest_cant_update_post()
    {
        $post = Post::factory()->create();

        $response = $this->put('/post/update', [

        ]);

        $response->assertStatus(302)
            ->assertRedirect('login');
    }

    public function test_the_guest_cant_delete_post()
    {
        $post = Post::factory()->create();

        $response = $this->get('/post/delete/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('login');
    }
}
