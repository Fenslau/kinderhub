<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class ActiveScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (method_exists($model, 'profile')) {
            $builder->whereHas('profile', function ($query) {
                $query->where('is_active', 1);
            });
        } else {
            $builder->where('is_active', 1);
        }
    }
}
