@extends('layouts.app')

@section('title-block', 'Статьи')
@section('description-block', 'Статьи про Ла2')

@section('breadcrumbs', Breadcrumbs::render('article', $article))
@section('content')

<div class="my-3 container-lg main">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h1 class="card-title">{{ $article->title }}</h1>

          <div class="my-3 card-text d-flex justify-content-between align-items-baseline">
            <a class="text-decoration-none" @empty($article->user?->id) @else href="{{ route('users.show', $article->user?->id ?? '') }}" @endempty>
              @empty($article->user?->profile?->image)
              <span class="text-muted align-middle"><i class="fa fa-user"></i></span>
              @else
              <img class="rounded-circle d-inline-block" style="max-height: 1.5rem;" src="{{ filter_var($article->user?->profile->image, FILTER_VALIDATE_URL) 
                ? $article->user?->profile->image 
                : Storage::url($article->user?->profile->image) }}" alt="">
              @endempty
              {{ $article->user->name }}
            </a>
            <small class="text-muted">Опубликовано: {{ \Carbon\Carbon::parse($article->created_at)->diffForHumans() }}</small>
          </div>

          @php
          $content = $article->content;
          $imageGroup = [];
          @endphp

          @foreach ($content as $element)
          @if ($element['type'] === 'editor')
          @if (!empty($imageGroup))
          @include('inc.carousel', ['images' => $imageGroup])
          @php
          @endphp
          @endif
          <div class="mt-5 card-text">
            {!! $element['data']['editor'] !!}
          </div>
          @elseif ($element['type'] === 'image')
          @php
          $imageGroup[] = $element['data'];
          @endphp
          @endif
          @endforeach
          @if (!empty($imageGroup))
          @if (count($imageGroup) === 1)
          <div class="d-flex justify-content-center">
            <img style="max-height: 400px;" class="img-fluid rounded-3 my-3" src="{{ Storage::url($imageGroup[0]['url']) }}" alt="{{ $imageGroup[0]['title'] }}">
          </div>
          @else
          @include('inc.carousel', ['images' => $imageGroup])
          @endif
          @endif

        </div>

        <ul class="list-group list-group-flush border-secondary">
          <li class="list-group-item text-muted small">
            @include('inc.article-tags')
          </li>
        </ul>

        <div class="card-footer d-flex justify-content-between">
          <div class="">

            <a role='button' class="me-2 text-decoration-none">
              {{ $article->commentsCount() }}
              <i class="fa fa-comments"></i>
            </a>
          </div>
          <div class="ya-share2" data-curtain data-services="vkontakte,odnoklassniki,telegram,whatsapp"></div>
        </div>
      </div>


      <div id="article_comments">
        <form class="my-3" action="{{ route('comments.store') }}" method="post">
          @csrf
          <div class="text-end">
            <input type="hidden" name="article" value="{{ $article->id }}">
            <textarea name="text" placeholder="Комментарий..." class="form-control mb-1" id="" rows="3"></textarea>
            <button type="submit" class="btn btn-outline-primary btn-sm">Отправить</button>
          </div>
        </form>

        @forelse($article->comments as $comment)
        @include('inc.comment')
        @foreach($comment->comments as $comment)
        @include('inc.comment')
        @foreach($comment->comments as $comment)
        @include('inc.comment')
        @endforeach
        @endforeach
        @empty
        <p class="text-muted">Нет комментариев</p>
        @endforelse
      </div>
    </div>
  </div>

</div>

@endsection