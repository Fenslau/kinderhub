<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvadersXX\FilamentNestedList\Concern\ModelNestedList;

class CareCategory extends Model
{
    use ModelNestedList;

    protected $guarded = [];

    public static function getAllDescendants($parentId): Collection
    {
        $descendants = collect();
        $model = new static();
        $children = $model->buildSortQuery()
            ->where($model->determineParentColumnName(), $parentId)
            ->get();
        foreach ($children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge(static::getAllDescendants($child->getKey()));
        }
        return $descendants;
    }
}
