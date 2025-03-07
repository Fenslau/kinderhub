<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Repositories\ReadOnlyRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct(
        protected ReadOnlyRepositoryInterface $repo,
    ) {}

    public function index(Request $request): View
    {
        $cities = $this->repo->index($request->all());
        return view('city.index', compact('cities'));
    }

    public function show(City $city): View
    {
        $city = $this->repo->show($city);
        return view('city.show', compact('city'));
    }
}
