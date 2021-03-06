<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function list(): Collection
    {
        return Post::with('ratingValues')->get();
    }

    public function store(array $data): void
    {
        $post = new Post;
        $post->fill($data);

        /** @var User $user */
        $user = Auth::user();
        $user->posts()->save($post);
    }

    public function update(array $data): void
    {
        Post::query()->where('id', $data['id'])
            ->update([
                'title' => $data['title'],
                'text' => $data['text']
            ]);
    }

    public function destroy(Post $post): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($post->isCreatedByUser($user)) {
            $post->delete();
        }
    }
}
