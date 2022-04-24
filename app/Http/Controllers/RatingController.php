<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\RatingService;
use Illuminate\Http\RedirectResponse;

class RatingController extends Controller
{
    public function __construct(
        private RatingService $ratingService
    ) {}

    public function up(Post $post): RedirectResponse
    {
        $this->ratingService->up($post);

        return redirect()->route('post.index');
    }

    public function down(Post $post): RedirectResponse
    {
        $this->ratingService->down($post);

        return redirect()->route('post.index');
    }
}
