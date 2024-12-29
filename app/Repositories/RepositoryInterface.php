<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function index(array $params = array());
    public function show($model);
    public function create(array $modelData);
    public function update($model, array $modelData);
    public function delete($model);
}
