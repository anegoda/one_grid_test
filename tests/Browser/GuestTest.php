<?php

namespace Tests\Browser;

use App\Models\Post;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GuestTest extends DuskTestCase
{
    public function testGuestSeeNoPosts()
    {
        $this->artisan('migrate:fresh');
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('No posts! Log in and create your own!');
        });
    }

    public function testGuestSeePost()
    {
        $this->artisan('migrate:fresh');
        $post = Post::factory()->create();
        $this->browse(function (Browser $browser) use ($post) {
            $browser->visit('/')
                ->assertSee($post->title);
        });
    }

    public function testGuestNotSeeOwnerButtonsOnPost()
    {
        $this->artisan('migrate:fresh');
        $post = Post::factory()->create();
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertMissing('.rating')
                ->assertMissing('.btns a');
        });
    }

    public function testGuestCanSeeLoginPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Log in')
                ->assertSee('Register');
        });
    }

    public function testGuestCanLogin()
    {
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Email')
                ->type('email', 'user1@mail.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/')
                ->assertDontSee('Log in')
                ->assertDontSee('Register')
                ->assertSee('Home')
                ->assertSee('Create Post')
                ->assertSee('Dashboard');
        });
    }
}
