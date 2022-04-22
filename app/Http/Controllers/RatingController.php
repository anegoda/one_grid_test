<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\RatingService;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function __construct(
        public RatingService $ratingService
    ) {}

    public function up(Post $post)
    {
        /** @var User $user */
        $user = Auth::user();

        $this->ratingService->up($post, $user);

        return redirect()->route('post.index');
    }

    public function down(Post $post)
    {
        /** @var User $user */
        $user = Auth::user();

        $this->ratingService->down($post, $user);

        return redirect()->route('post.index');
    }
}
