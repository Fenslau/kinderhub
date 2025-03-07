@extends('layouts.app')

@section('title-block', $city->name)
@section('description-block', '')

@section('breadcrumbs', Breadcrumbs::render('city', $city))
@section('content')

<div class="my-3 container-lg main">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h1 class="card-title">{{ $city->name }}</h1>

          <div class="my-3 card-text d-flex justify-content-between align-items-center">
            <div class="text-end">
              <a target="_blank" rel="nofollow" href="{{ $city->wiki }}">{{ $city->wiki }}</a>
            </div>
          </div>

          <div class="my-3">
            <hr />
          </div>

          @empty(count($city->users))
          <h5 class="mt-3 text-muted">Пользователи из этого города не зарегистрированы</h5>
          @else
          <h5 class="mt-3">Пользователи из этого города:</h5>
          <div class="d-flex flex-wrap">
            @foreach($city->users->sortByDesc('created_at')->take(config('constants.defines.last_users_per_city')) as $user)
            @include('user.list')
            @endforeach
          </div>
          <div class="small text-muted text-end">
            *В скобках указано количество комментариев
          </div>
          @endempty

        </div>
      </div>
    </div>
  </div>

</div>

@endsection