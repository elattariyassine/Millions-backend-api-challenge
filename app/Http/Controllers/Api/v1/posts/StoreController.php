<?php

namespace App\Http\Controllers\Api\v1\posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\posts\StorePostRequest;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewPostNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function __invoke(StorePostRequest $request): JsonResponse
    {
        $createdPost = Post::query()->create(array_merge(
            $request->validated(),
            ["image" => $request->file('image')->store('posts/', 'public')],
            ['user_id' => Auth::id()]
        ));

        $otherUsers = User::query()->whereKeyNot(Auth::id())->get();

        Notification::send($otherUsers, new NewPostNotification($createdPost, Auth::user()));

        return response()->json($createdPost, Response::HTTP_CREATED);
    }
}
