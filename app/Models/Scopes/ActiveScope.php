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
        $table = $model->getTable();
        if (Schema::hasColumn($table, 'is_active')) {
            $builder->where($table . '.is_active', 1);
        } elseif (method_exists($model, 'profile')) {
            $builder->whereHas('profile', function ($query) use ($model) {
                $profileTable = $model->profile()->getRelated()->getTable();
                if (Schema::hasColumn($profileTable, 'is_active')) {
                    $query->where('is_active', 1);
                }
            });
        }
    }
}
