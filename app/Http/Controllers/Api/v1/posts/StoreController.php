<?php

namespace App\Http\Controllers\Api\v1\posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\posts\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function __invoke(StorePostRequest $request): JsonResponse
    {
        $fields = $request->validated();
        $fields['image'] = $request->file('image')->store('posts/', 'public');
        $createdPost = Post::query()->create($fields + ['user_id' => Auth::id()]);

        return response()->json($createdPost, Response::HTTP_CREATED);
    }
}
