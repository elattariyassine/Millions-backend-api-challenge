<?php

namespace App\Http\Controllers\Api\v1\posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DestroyController extends Controller
{
    public function __invoke(Post $post): Response
    {
        Gate::authorize('destroy-post', $post);

        if (Storage::disk('public')->exists($post->image)) {
            Storage::delete($post->image);
        }

        $post->delete();

        return response()->noContent();
    }
}
