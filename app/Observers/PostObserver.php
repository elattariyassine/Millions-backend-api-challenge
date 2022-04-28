<?php

namespace App\Observers;

use App\Events\PostCreatedEvent;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    public function created(Post $post)
    {
        PostCreatedEvent::dispatch($post);
    }

    public function deleting(Post $post)
    {
        if (Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }
    }
}
