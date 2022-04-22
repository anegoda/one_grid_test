<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;

class RatingService
{
    public function isPostRatedByUser(Post $post, User $user): bool
    {
        return $post->users()->where('user_id', $user->id)->exists();
    }

    public function isPostCreatedByUser(Post $post, User $user): bool
    {
        return $post->user_id == $user->id;
    }

    public function up(Post $post, User $user): void
    {
        if (
            !$this->isPostRatedByUser($post, $user) &&
            !$this->isPostCreatedByUser($post, $user)
        ) {
            $post->users()->sync([
                $user->id => [
                    'value' => Post::RATE_UP_VALUE
                ]
            ]);

            Post::query()->where('id', $post->id)->increment('rating');
        }
    }

    public function down(Post $post, User $user): void
    {
        if (
            !$this->isPostRatedByUser($post, $user) &&
            !$this->isPostCreatedByUser($post, $user)
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
