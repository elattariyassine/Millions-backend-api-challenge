<?php

namespace App\Http\Controllers\Api\v1\posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LikesController extends Controller
{
    public function __invoke(Post $post): JsonResponse
    {
        $postLikes = $post
            ->likes()
            ->with('user')
            ->get()
            ->pluck('user.name');

        return response()->json(['data' => $postLikes], Response::HTTP_OK);
    }
}
