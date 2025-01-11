<?php

namespace App\Repositories;

use DragonCode\Contracts\Cashier\Resources\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function index(array $params = array()): LengthAwarePaginator;
    public function show(string|Model $model): Model;
    public function create(array $modelData);
    public function update($model, array $modelData);
    public function delete($model);
}
