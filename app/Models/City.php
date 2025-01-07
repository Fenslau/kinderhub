<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function profiles()
    {
        return $this->hasMany(UserProfile::class);
    }

    public function articles()
    {
        return $this->hasManyThrough(Article::class, User::class)->where('articles.is_active', true);
    }
}
