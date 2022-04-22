<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\Store;
use App\Http\Requests\Post\Update;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\PostService;

class PostController extends Controller
{
    public function __construct(
        public PostService $postService
    ) {}

    public function index(): View
    {
//        dd(Post::with('ratingValues')->get());
        return view('welcome', [
            'posts' => Post::with('ratingValues')->get()
        ]);
    }

    public function create(): View
    {
        return view('post.create');
    }

    public function store(Store $request): RedirectResponse
    {
        $post = new Post;
        $post->fill($request->all());

        /** @var User $user */
        $user = Auth::user();
        $user->posts()->save($post);

        return redirect()->route('post.index');
    }

    public function edit(Post $post)
    {
        return view('post.edit', [
            'post' => $post
        ]);
    }

    public function update(Update $request)
    {
        Post::query()->where('id', $request->get('id'))
            ->update([
                'title' => $request->get('title'),
                'text' => $request->get('text')
            ]);

        return redirect()->route('post.index');
    }

    public function destroy(Post $post)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($this->postService->isPostCreatedByUser($post, $user)) {
            $post->delete();
        }
        return redirect()->route('post.index');
    }
}
