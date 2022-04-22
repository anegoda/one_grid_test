<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;

class PostService
{
    public function isPostCreatedByUser(Post $post, User $user): bool
    {
        return $post->user_id == $user->id;
    }
}
