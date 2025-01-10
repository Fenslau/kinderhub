<?php

namespace App\Traits;

use App\Models\Scopes\ActiveScope;

trait Activeable
{
    public static function booted()
    {
        static::addGlobalScope(new ActiveScope);
    }
}
