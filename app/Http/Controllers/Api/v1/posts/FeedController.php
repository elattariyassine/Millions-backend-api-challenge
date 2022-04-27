<?php

namespace App\Http\Controllers\Api\v1\posts;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\PostFeedResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FeedController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $posts = Post::query()
            ->latest()
            ->withCount('likes')
            ->with('user')
            ->withLastReacters(5)
            ->get();

        return PostFeedResource::collection($posts);
    }
}
