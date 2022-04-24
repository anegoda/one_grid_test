<?php

namespace Tests\Browser;

use App\Models\Post;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserTest extends DuskTestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed', ['--class' => 'UserSeeder']);

        /** @var User user */
        $user = User::query()->where('email', 'user1@mail.com')->first();
        $this->user = $user;
    }

    public function testLoggedUserSeeNoPosts()
    {
        $user = $this->user;
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                ->visit('/')
                ->assertSee('No posts! Log in and create your own!');
        });
    }

    public function testLoggedUserCanCreatePost()
    {
        $user = $this->user;
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                ->visit('/post/create')
                ->type('title', 'Test post 123')
                ->type('text', 'Text for Test post 123')
                ->press('Create')
                ->assertPathIs('/')
                ->assertSee('Test post 123')
                ->assertSee('Text for Test post 123');
        });
    }

    public function testLoggedUserCanEditOwnPost()
    {
        $user = $this->user;
        $post = Post::factory()->create([
            'user_id' => $user->id
        ]);
        $this->browse(function (Browser $browser) use ($user, $post) {
            $browser->loginAs($user->id)
                ->visit('/')
                ->assertSee($post->title)
                ->assertSee($post->text)
                ->click('.btns a.btn-warning')
                ->assertPathIs('/post/edit/' . $post->id)
                ->type('title', 'Test post 123')
                ->type('text', 'Text for Test post 123')
                ->press('Update')
                ->assertPathIs('/')
                ->assertSee('Test post 123')
                ->assertSee('Text for Test post 123');
        });
    }

    public function testLoggedUserCanDeleteOwnPost()
    {
        $user = $this->user;
        $post = Post::factory()->create([
            'user_id' => $user->id
        ]);
        $this->browse(function (Browser $browser) use ($user, $post) {
            $browser->loginAs($user->id)
                ->visit('/')
                ->assertSee($post->title)
                ->assertSee($post->text)
                ->click('.btns a.btn-danger')
                ->assertPathIs('/')
                ->assertSee('No posts! Log in and create your own!');
        });
    }

    public function testLoggedUserDontSeeRatingButtonForOwnPost()
    {
        $user = $this->user;
        $post = Post::factory()->create([
            'user_id' => $user->id
        ]);
        $this->browse(function (Browser $browser) use ($user, $post) {
            $browser->loginAs($user->id)
                ->visit('/')
                ->assertMissing('.rating');
        });
    }

    public function testLoggedUserCanSeePostRateOfAnotherUser()
    {
        $user = $this->user;
        Post::factory()->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                ->visit('/')
                ->assertVisible('.rating');
        });
    }

    public function testLoggedUserCanRateUpPostOfAnotherUser()
    {
        $user = $this->user;
        Post::factory()->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                ->visit('/')
                ->click('.rating .thumb-up a')
                ->assertPathIs('/')
                ->assertSeeIn('.current-rating', '1');
        });
    }

    public function testLoggedUserCanRateDownPostOfAnotherUser()
    {
        $user = $this->user;
        Post::factory()->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                ->visit('/')
                ->click('.rating .thumb-down a')
                ->assertPathIs('/')
                ->assertSeeIn('.current-rating', '-1');
        });
    }
}
