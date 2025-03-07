@extends('layouts.app')

@section('title-block', $user->name)
@section('description-block', 'Пользователь проекта ' . config('app.name'))

@section('breadcrumbs', Breadcrumbs::render('user', $user))
@section('content')

<div class="my-3 container-lg main">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h1 class="card-title">{{ $user->name }}</h1>

          <div class="my-3 card-text d-flex justify-content-between align-items-center">
            @include('user.avatar', ['user' => $user, 'maxHeight' => 6, 'xHeight' => 5])
            <div class="text-end">
              <a href="{{ route('cities.show', $user->city ?? 0) }}">
                <span>{{ $user->city?->type }} {{ $user->city?->name }}</span><br />
              </a>
              <small class="text-muted">Зарегистрирован: {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</small> <br />
              <small class="text-muted">{!! $user->profile->about !!}</small> <br />
              <small class="text-muted">Комментариев: <b>{{ $user->comments_count }}</b></small> <br />
            </div>
          </div>

          @empty(count($user->comments))
          <h5 class="mt-5 text-muted">Пользователь не оставлял комментариев</h5>
          @else
          <h5 class="mt-5">Последние комментарии этого пользователя:</h4>
            @foreach($user->comments->sortByDesc('created_at')->take(config('constants.defines.last_comments')) as $comment)
            @include('inc.comment', ['article' => null])
            @endforeach
            @endempty
        </div>
      </div>
    </div>
  </div>

</div>

@endsection