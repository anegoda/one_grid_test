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

    public function testTheApplicationReturnsASuccessfulResponseForLoggedUser()
    {
        $response = $this->actingAs($this->user)->get('/');

        $response->assertStatus(200);
    }

    public function testUserCanCreatePost()
    {
        $response = $this->actingAs($this->user)->get(route('post.create'));

        $response->assertStatus(200);
    }

    public function testUserCanStorePost()
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

    public function testUserCanEditOwnPost()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get('/post/edit/' . $post->id);

        $response->assertStatus(200);
    }

    public function testUserCantEditPostOfAnotherUser()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)->get('/post/edit/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect(route('post.index'));
    }

    public function testUserCanUpdateOwnPost()
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

    public function testUserCantUpdatePostOfAnotherUser()
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

    public function testUserCanDeleteOwnPost()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get('/post/delete/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $this->assertDatabaseMissing('posts', $post->toArray());
    }

    public function testUserCantDeletePostOfAnotherUser()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)->get('/post/delete/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $this->assertDatabaseHas('posts', $post->toArray());
    }

    public function testUserCantVoteUpOwnPost()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get('/post/rating/up/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $this->assertDatabaseHas('posts', $post->toArray());
    }

    public function testUserCanVoteUpPostOfAnotherUser()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)->get('/post/rating/up/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $post->rating += 1;
        $this->assertDatabaseHas('posts', $post->toArray());
    }

    public function testUserCantVoteDownOwnPost()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get('/post/rating/down/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $this->assertDatabaseHas('posts', $post->toArray());
    }

    public function testUserCanVoteDownPostOfAnotherUser()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)->get('/post/rating/down/' . $post->id);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $post->rating -= 1;
        $this->assertDatabaseHas('posts', $post->toArray());
    }
}
