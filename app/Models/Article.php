<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes, Sluggable;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'content' => 'json',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->whereActive(true)->orderBy('created_at', 'asc');
    }

    public function isActive(): bool
    {
        return $this->is_active === 1 && !$this->trashed();
    }

    public function isGlobal(): bool
    {
        return $this->is_global === 1 && $this->isActive();
    }
}
