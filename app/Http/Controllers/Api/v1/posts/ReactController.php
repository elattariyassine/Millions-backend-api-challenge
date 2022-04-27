<?php

namespace App\Http\Controllers\Api\v1\posts;

use App\Http\Controllers\Controller;
use App\Models\PostLike;
use Illuminate\Support\Facades\Auth;

class ReactController extends Controller
{
    public function __invoke(string $postUUID): PostLike
    {
        if ($post = Auth::user()->postLikes()->firstWhere('post_id', $postUUID)) {
            return tap($post)->delete();
        }

        return Auth::user()->postLikes()->create(['post_id' => $postUUID]);
    }
}
