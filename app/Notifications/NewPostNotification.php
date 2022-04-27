<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewPostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Post $post, private User $user)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'user' => $this->user->name,
            'post' => $this->post->description
        ];
    }
}
