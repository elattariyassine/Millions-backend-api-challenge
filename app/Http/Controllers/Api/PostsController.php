<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StorePostRequest;
use App\Http\Resources\Api\PostFeedResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->latest()
            ->withCount('likes')
            ->with('user')
            ->withLastReacters(5)
            ->paginate(10);

        return response()->json(PostFeedResource::collection($posts)->response()->getData(true));
    }

    public function store(StorePostRequest $request)
    {
        $post = Auth::user()->posts()->create(array_merge(
            $request->validated(),
            ["image" => $request->file('image')->store('posts/', 'public')]
        ));

        return response()->json($post, Response::HTTP_CREATED);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }

    public function likes(Post $post)
    {
        $likes = $post->likes()
            ->with('user')
            ->get()
            ->pluck('user.name');

        return response()->json($likes, Response::HTTP_OK);
    }

    public function react(Post $post)
    {
        Auth::user()->toggleLike($post);

        return response()->noContent();
    }
}
