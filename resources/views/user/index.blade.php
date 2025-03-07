@extends('layouts.app')

@section('title-block', 'Пользователи')
@section('description-block', 'Пользователи проекта ' . config('app.name'))

@section('breadcrumbs', Breadcrumbs::render('users'))
@section('content')

<div class="my-3 container-lg main">

  <div class="row">
    <div class="col">
      <h1 class="">Все пользователи</h1>
      <div class="my-3 d-flex flex-wrap">
        @foreach($users as $user)
        @include('user.list')
        @endforeach
      </div>
      <div class="small text-muted text-end">
        *В скобках указано количество комментариев
      </div>
      {{ $users->links() }}

    </div>
  </div>

</div>
@endsection