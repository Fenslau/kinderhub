<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ReadOnlyRepositoryInterface
{
    public function index(array $params = array()): Collection;
    public function show(string|Model $model): Model;
}
