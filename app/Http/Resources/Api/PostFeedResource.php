<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PostFeedResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'image' => $this->image,
            'description' => $this->description,
            'date' => $this->created_at,
            'author' => $this->user->name,
            'total_likes' => $this->likes_count,
            'reacters' => $this->whenLoaded('likes', fn() => $this->likes->pluck('user.name')),
        ];
    }
}
