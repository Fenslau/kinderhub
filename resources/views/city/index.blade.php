@extends('layouts.app')

@section('title-block', 'Города')
@section('description-block', 'Города проекта ' . config('app.name'))

@section('breadcrumbs', Breadcrumbs::render('cities'))
@section('content')

<div class="my-3 container-lg main">

  <div class="row">
    <div class="col">
      <h1 class="">Все города</h1>
      <div class="my-3 d-flex flex-wrap">
        @foreach($cities as $city)
        @include('city.list')
        @endforeach
      </div>
      <div class="small text-muted text-end">
        *В скобках указано количество пользователей
      </div>
      {{ $cities->links() }}

    </div>
  </div>

</div>

@endsection