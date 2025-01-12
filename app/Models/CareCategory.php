<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareCategory extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'sub_category' => 'array'
        ];
    }
}
