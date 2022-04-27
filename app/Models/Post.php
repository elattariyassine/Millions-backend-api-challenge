<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Post extends Model
{
    use HasFactory, HasUuid, HasEagerLimit;

    protected $fillable = ['uuid', 'description', 'image', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class, 'post_id', 'uuid');
    }

    public function scopeWithLastReacters(Builder $query,int $count = 5): Builder
    {
        return $query->with('likes', function (HasMany $query) use ($count) {
            return $query->latest()->with('user')->limit($count);
        });
    }
}
