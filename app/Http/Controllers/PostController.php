<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\Store;
use App\Http\Requests\Post\Update;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\PostService;

class PostController extends Controller
{
    public function __construct(
        private PostService $postService
    ) {}

    public function index(): View
    {
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
        $this->postService->store($request->all());

        return redirect()->route('post.index');
    }

    public function edit(Post $post): View
    {
        return view('post.edit', [
            'post' => $post
        ]);
    }

    public function update(Update $request): RedirectResponse
    {
        $this->postService->update($request->all());

        return redirect()->route('post.index');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->postService->destroy($post);

        return redirect()->route('post.index');
    }
}
