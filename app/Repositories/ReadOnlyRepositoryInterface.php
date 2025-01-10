<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ReadOnlyRepositoryInterface
{
    public function index(array $params = array()): LengthAwarePaginator;
    public function show(string|Model $model): Model;
}
