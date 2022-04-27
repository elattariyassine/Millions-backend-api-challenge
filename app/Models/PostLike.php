<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class PostLike extends Model
{
    use HasFactory, HasUuid, HasEagerLimit;

    protected $fillable= ['user_id', 'post_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'uuid');
    }
}
