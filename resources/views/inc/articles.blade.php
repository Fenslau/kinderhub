<div class="row g-4">
  @foreach ($articles as $article)
  <div class="w-100">
    <div class="card border-primary h-100">
      <div class="card-header">
        <a class="nav-link link-body-emphasis pb-0" aria-current="true" href="{{ route('articles.show', $article->slug) }}">
          <h2 style="color: inherit" class="my-0">{{ $article->title }}</h2>
        </a>
      </div>
      @if(!empty(collect($article->content)->firstWhere('type', 'image')['data']['url']))
      <a class="m-auto" href="{{ route('articles.show', $article->slug) }}">
        <img style="max-height: 400px;"
          src="{{ Storage::url(collect($article->content)->firstWhere('type', 'image')['data']['url']) }}"
          class="img-fluid" alt="{{ collect($article->content)->firstWhere('type', 'image')['data']['title'] }}">
      </a>
      @endif
      <div style="max-height:400px;" class="card-body position-relative overflow-hidden">

        <div class="mb-3 card-text d-flex justify-content-between align-items-baseline">
          <div>
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
          </div>
          <div>
            <small class="card-text text-muted">{{ \Carbon\Carbon::parse($article->created_at)->diffForHumans() }}
            </small>
          </div>
        </div>


        <div class="card-text position-relative overflow-hidden text-overflow-container">
          @empty($article->highlights)
          {!! collect($article->content)->firstWhere('type', 'editor')['data']['editor'] ?? '' !!}
          @else
          {!! $article->highlights !!}
          @endempty
        </div>
      </div>

      <ul class="list-group list-group-flush border-secondary">
        <li class="list-group-item text-muted small">
          @include('inc.article-tags')
        </li>
      </ul>
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