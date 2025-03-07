<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class City extends Model
{
    use HasFactory;

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, UserProfile::class, 'city_id', 'id', 'id', 'user_id');
    }

    public function articles()
    {
        return $this->hasManyThrough(Article::class, User::class);
    }
}
