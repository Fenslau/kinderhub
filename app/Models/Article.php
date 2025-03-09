<?php

namespace App\Models;

use App\Traits\Activeable;
use App\Traits\Commentable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Article extends Model
{
    use SoftDeletes, Sluggable, Activeable, Commentable, HasTags;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'content' => 'json',
            'is_active' => 'boolean',
            'is_global' => 'boolean'
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

    protected $with = [
        'user',
        'tags',
        'comments'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at', 'asc');
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isGlobal(): bool
    {
        return $this->is_global && $this->isActive();
    }
}
