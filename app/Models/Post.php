<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Post extends Model
{
    use HasFactory,
        HasUuid,
        HasEagerLimit,
        Prunable;

    protected $fillable = [
        'description',
        'image',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class, 'post_id', 'uuid');
    }

    public function scopeWithLastReacters(Builder $query,int $count = 5)
    {
        return $query->with('likes', fn (HasMany $query) => $query->latest()->with('user')->limit($count));
    }

    public function prunable()
    {
        return static::whereDate('created_at', '<=',now()->subDays(15));
    }
}
