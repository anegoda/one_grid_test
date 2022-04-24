<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->user = $user;
    }

    public function test_the_application_returns_a_successful_response_for_logged_user()
    {
        $response = $this->actingAs($this->user)->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_create_post()
    {
        $response = $this->actingAs($this->user)->get(route('post.create'));

        $response->assertStatus(200);
    }

    public function test_user_can_store_post()
    {
        $data = [
            'title' => $this->faker->text(50),
            'text' => $this->faker->text(),
        ];

        $response = $this->actingAs($this->user)->post('/post/store', $data);

        $response->assertStatus(302)
            ->assertRedirect(route('post.index'));

        $this->assertDatabaseHas('posts', $data);
    }

    public function test_user_can_edit_own_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get('/post/edit/' . $post->id);

        $response->assertStatus(200);
    }

    public function test_user_cant_edit_post_of_another_user()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)->get('/post/edit/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect(route('post.index'));
    }

    public function test_user_can_update_own_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $data = [
            'id' => $post->id,
            'title' => $this->faker->text(50),
            'text' => $this->faker->text(),
        ];

        $response = $this->actingAs($this->user)->put('/post/update', $data);

        $response->assertStatus(302)
            ->assertRedirect(route('post.index'));

        $this->assertDatabaseHas('posts', $data);
    }

    public function test_user_cant_update_post_of_another_user()
    {
        $post = Post::factory()->create();

        $data = [
            'id' => $post->id,
            'title' => $this->faker->text(50),
            'text' => $this->faker->text(),
        ];

        $response = $this->actingAs($this->user)->put('/post/update', $data);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('posts', $data);
    }

    public function test_user_can_delete_own_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get('/post/delete/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $this->assertDatabaseMissing('posts', $post->toArray());
    }

    public function test_user_cant_delete_post_of_another_user()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)->get('/post/delete/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $this->assertDatabaseHas('posts', $post->toArray());
    }

    public function test_user_cant_vote_up_own_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get('/post/rating/up/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $this->assertDatabaseHas('posts', $post->toArray());
    }

    public function test_user_can_vote_up_post_of_another_user()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)->get('/post/rating/up/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $post->rating += 1;
        $this->assertDatabaseHas('posts', $post->toArray());
    }

    public function test_user_cant_vote_dow_own_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get('/post/rating/down/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $this->assertDatabaseHas('posts', $post->toArray());
    }

    public function test_user_can_vote_down_post_of_another_user()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)->get('/post/rating/down/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $post->rating -= 1;
        $this->assertDatabaseHas('posts', $post->toArray());
    }
}
