<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RatingService
{
    public function up(Post $post): void
    {
        /** @var User $user */
        $user = Auth::user();

        if (
            !$post->isRatedByUser($user) &&
            !$post->isCreatedByUser($user)
        ) {
            $post->users()->sync([
                $user->id => [
                    'value' => Post::RATE_UP_VALUE
                ]
            ]);

            Post::query()->where('id', $post->id)->increment('rating');
        }
    }

    public function down(Post $post): void
    {
        /** @var User $user */
        $user = Auth::user();

        if (
            !$post->isRatedByUser($user) &&
            !$post->isCreatedByUser($user)
        ) {
            $post->users()->sync([
                $user->id => [
                    'value' => Post::RATE_DOWN_VALUE
                ]
            ]);

            Post::query()->where('id', $post->id)->decrement('rating');
        }
    }
}
