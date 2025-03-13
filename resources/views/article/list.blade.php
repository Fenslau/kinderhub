<div class="row g-4">
  @foreach ($articles as $article)
  <div class="w-100">
    <div class="card h-100">
      <div class="card-header position-relative">
        <a class="nav-link pb-0 d-flex justify-content-between" aria-current="true" href="{{ route('articles.show', $article->slug) }}">
          <h2 class="my-0">{{ $article->title }}</h2>
          <div class="d-flex justify-content-around align-items-center">
            @if($article->isGlobal())
            <span class="opacity-75 badge rounded-pill text-bg-secondary"
              data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Закреплено">
              <i class="fa fa-thumb-tack" aria-hidden="true"></i>
            </span>
            @endif
          </div>
        </a>
      </div>
      <div class="card-body">
        <div class="card-text d-flex justify-content-between align-items-baseline">
          <div>
            <a class="text-decoration-none" @empty($article->user?->id) @else href="{{ route('users.show', $article->user?->id ?? '') }}" @endempty>
              @include('user.avatar', ['user' => $article->user])
              {{ $article->user->name }}
            </a>
          </div>
          <div>
            <small class="card-text text-muted">{{ \Carbon\Carbon::parse($article->created_at)->diffForHumans() }}
            </small>
          </div>
        </div>
      </div>
      @if(!empty(collect($article->content)->firstWhere('type', 'image')['data']['url']))
      <a class="m-auto" href="{{ route('articles.show', $article->slug) }}">
        <img style="max-height: 400px;"
          src="{{ Storage::url(collect($article->content)->firstWhere('type', 'image')['data']['url']) }}"
          class="img-fluid" alt="{{ collect($article->content)->firstWhere('type', 'image')['data']['title'] }}">
      </a>
      @endif
      <div style="max-height:400px;" class="card-body position-relative overflow-hidden">
        <div class="card-text position-relative overflow-hidden">
          @empty($article->highlights)
          {!! collect($article->content)->firstWhere('type', 'editor')['data']['editor'] ?? '' !!}
          @else
          {!! $article->highlights !!}
          @endempty
        </div>
      </div>

      @if(count($article->tags))
      <ul class="list-group list-group-flush border">
        <li class="list-group-item text-muted small">
          @include('article.tags')
        </li>
      </ul>
      @endif

      <div class="card-footer">
        <div class="d-flex justify-content-between">
          <div class="">
            <a role='button' class="text-decoration-none"
              href="{{ route('articles.show', $article->slug) }}#article_comments">
              @empty($article->commentsCount())

              @else
              {{ $article->commentsCount() }}
              @endempty
              <i class="fa fa-comments"></i>
            </a>
          </div>

          <a href="{{ route('articles.show', $article->slug) }}" class="card-link">Посмотреть</a>
        </div>

      </div>
    </div>
  </div>
  @endforeach
</div>
<div class="my-3">
  @if($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
  {{ $articles->links() }}
  @endif
</div>