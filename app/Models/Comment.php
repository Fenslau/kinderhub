<?php

namespace App\Models;

use App\Enums\CommentableEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mews\Purifier\Casts\CleanHtml;

class Comment extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'text' => CleanHtml::class,
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault(function ($user, $comment) {
            $user->name = 'Неизвестен';
            $user->id = 0;
        });
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->whereActive(true)->orderBy('created_at', 'asc');
    }

    public function isActive(): bool
    {
        return $this->is_active === 1 && !$this->trashed();
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function (Comment $comment) {
            $comment->link = $comment->generateLink();
        });
    }

    protected function generateLink(): string
    {
        $entity = $this->getCommentableEntity();
        $slug = $entity->slug ?? '';
        $route = match (true) {
            $entity instanceof Article => 'articles.show',

            default => 'home',
        };
        return route($route, $slug) . "#comment_show{$this->id}";
    }

    protected function getCommentableEntity(): ?Model
    {
        $entity = $this->commentable;
        $validClasses = array_filter(
            array_column(CommentableEnum::cases(), 'value'),
            fn($class) => $class !== CommentableEnum::COMMENT->value
        );
        while ($entity && !in_array(get_class($entity), $validClasses)) {
            $entity = $entity->commentable ?? null;
        }
        return $entity;
    }
}
