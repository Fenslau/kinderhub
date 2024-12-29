<?php

namespace App\Repositories;

interface ReadOnlyRepositoryInterface
{
    public function index(array $params = array());
    public function show($model);
}
