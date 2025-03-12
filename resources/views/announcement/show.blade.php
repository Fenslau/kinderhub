@extends('layouts.app')

@section('title-block', $announcement->title)
@section('description-block', $announcement->description ?? '')

@section('breadcrumbs', Breadcrumbs::render('announcement', $announcement))
@section('content')

<div class="my-3 container-lg main">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h1 class="card-title">{{ $announcement->title }}</h1>
          <span @class([ "text-bg-{$announcement->type->getColor()}" , "opacity-75 position-absolute top-0 end-0 badge rounded-pill" ,
            ])>
            {{ $announcement->type->getLabel() }}
          </span>

          <div class="my-3 card-text d-flex justify-content-between align-items-baseline">
            <a class="text-decoration-none" @empty($announcement->user?->id) @else href="{{ route('users.show', $announcement->user?->id ?? '') }}" @endempty>
              @include('user.avatar', ['user' => $announcement->user])
              {{ $announcement->user->name }}
            </a>
            <small class="text-muted">Опубликовано: {{ \Carbon\Carbon::parse($announcement->created_at)->diffForHumans() }}</small>
          </div>

          <div class="mt-3 card-text">
            {!! $announcement['content'] !!}
          </div>

        </div>

        @if(!empty($announcement->sub_category))
        <ul class="list-group list-group-flush border">
          <li class="list-group-item text-muted small">
            @include('announcement.categories')
          </li>
        </ul>
        @endif

        <div class="card-footer d-flex justify-content-between">
          <div class="">

          </div>
          <div class="ya-share2" data-curtain data-services="vkontakte,odnoklassniki,telegram,whatsapp"></div>
        </div>
      </div>

    </div>
  </div>

</div>

@endsection