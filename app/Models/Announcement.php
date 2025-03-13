<?php

namespace App\Models;

use App\Enums\AnnouncementTypeEnum;
use App\Traits\Activeable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes, Sluggable, Activeable;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'type' => AnnouncementTypeEnum::class,
            'is_active' => 'boolean',
            'is_global' => 'boolean',
            'sub_category' => 'array'
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
        'careCategory'
    ];

    protected function subCategory(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value, JSON_UNESCAPED_UNICODE)
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function careCategory(): BelongsTo
    {
        return $this->belongsTo(CareCategory::class);
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
