<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvadersXX\FilamentNestedList\Concern\ModelNestedList;
use InvadersXX\FilamentNestedList\Support\Utils;

class CareCategory extends Model
{
    use ModelNestedList;

    protected $guarded = [];

    public static function processedSelectArray(?int $parentId = null, ?int $maxDepth = null): array
    {
        $result = [];

        $model = app(static::class);

        [$primaryKeyName, $titleKeyName, $parentKeyName, $childrenKeyName] = [
            $model->getKeyName(),
            $model->determineTitleColumnName(),
            $model->determineParentColumnName(),
            static::defaultChildrenKeyName(),
        ];

        $allNodes = static::allNodes()->toArray();

        $nodes = Utils::buildNestedArray(
            nodes: $allNodes,
            parentId: $parentId ?? static::defaultParentKey(),
            primaryKeyName: $primaryKeyName,
            parentKeyName: $parentKeyName,
            childrenKeyName: $childrenKeyName
        );

        foreach ($nodes as $node) {
            static::buildSelectArrayItem($result, $node, $primaryKeyName, $titleKeyName, $childrenKeyName, 1, $maxDepth);
        }

        foreach ($result as &$title) {
            $title = str_replace('-', ' ', $title);
        }

        return $result;
    }
}
