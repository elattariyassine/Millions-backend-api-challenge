<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasUuid;

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute(string $password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'uuid');
    }

    public function postLikes()
    {
        return $this->hasMany(PostLike::class, 'user_id', 'uuid');
    }

    public function react(Post $post)
    {
        if ($reaction = $this->postLikes()->firstWhere('post_id', $post->uuid)) {
            return $reaction->delete();
        }

        $this->postLikes()->create(['post_id' => $post->uuid]);
    }
}
