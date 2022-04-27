<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid(): void
    {
        static::creating(fn (Model $model) => $model->{self::uuidColumn()} = Str::uuid()->toString());
    }

    public function getRouteKeyName(): string
    {
        return self::uuidColumn();
    }

    protected static function uuidColumn(): string
    {
        return 'uuid';
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function getKeyName(): string
    {
        return self::uuidColumn();
    }
}
