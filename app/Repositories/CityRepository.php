<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CityRepository implements ReadOnlyRepositoryInterface
{
    public function index(array $params = array()): LengthAwarePaginator
    {
        $cities = City::query()
            ->has('users')
            ->withCount('users')
            ->orderBy('users_count', 'desc')
            ->paginate(config('constants.defines.cities_per_page'));
        return $cities;
    }

    public function show(string|Model $model): City
    {
        $city = $model->load([
            'users' => function ($query) {
                $query->withCount('comments');
            }
        ]);
        return $city;
    }
}
