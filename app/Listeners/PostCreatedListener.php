<?php

namespace App\Listeners;

use App\Events\PostCreatedEvent;
use App\Models\User;
use App\Notifications\PostCreatedNotification;
use Illuminate\Support\Facades\Notification;

class PostCreatedListener
{
    public function handle(PostCreatedEvent $postCreatedEvent)
    {
        Notification::send(
            User::query()->whereKeyNot($postCreatedEvent->post->user_id)->get(),
            new PostCreatedNotification($postCreatedEvent->post)
        );
    }
}
