<div class="border-start">
  <div @class([ 'card my-2' , 'ms-4'=> $comment->commentable instanceof \App\Models\Comment && (Route::is('articles.show') ),
    'ms-5' => $comment->commentable?->commentable instanceof \App\Models\Comment && Route::is('articles.show'),
    ]) id="comment_show{{ $comment->id }}">

    <div class="card-header p-1 d-flex justify-content-between align-items-baseline">
      <div class="overflow-hidden text-nowrap">
        @include('user.avatar', ['user' => $comment->user])
        <a @class([ 'text-decoration-none badge rounded-pill align-middle px-2' , 'text-bg-success'=> $comment->user?->id === $article?->user?->id,
          'text-bg-info' => $comment->user?->id === $authUser?->id && $comment->user?->id !== $article?->user?->id,
          'text-bg-light' => $comment->user?->id !== $authUser?->id && $comment->user?->id !== $article?->user?->id,
          ]) href="{{ route('users.show', $comment->user?->id) }}"><span class="fs-6">{{ $comment->user->name }}</span></a>

      </div>
      <div class="text-muted small text-truncate">
        {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
      </div>
    </div>
    <div class="card-body">
      <p class="card-text">
        {!! nl2br($comment->text) !!}
      </p>
    </div>
    <div class="card-footer py-1">
      <div class="d-flex justify-content-between align-items-baseline">
        <a class="small align-middle" data-bs-toggle="collapse" href="#comment{{ $comment->id }}" role="button">Ответить</a>
        @if(Route::is('users.show'))
        <a class="small align-middle" href="{{ $comment->link }}">Ссылка</a>
        @else
        <a class="small align-middle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Скопировать ссылку" role="button" onclick="navigator.clipboard.writeText('{{ $comment->link }}').then(() => {this.innerText = 'скопировано'})"><i class="fa fa-link"></i></a>
        @endif
      </div>

      <form class="collapse" id="comment{{ $comment->id }}" action="{{ route('comments.store') }}" method="post">
        @csrf
        <div class="text-end">
          <input type="hidden" name="comment"
          @if($comment->commentable?->commentable instanceof \App\Models\Comment)
          value="{{ $comment->commentable?->id }}"
          @else
          value="{{ $comment->id }}"
          @endif
          >
          <textarea name="text" placeholder="Комментарий..." class="form-control mb-1" id="" rows="3"></textarea>
          <button type="submit" class="btn btn-outline-primary btn-sm">Отправить</button>
        </div>
      </form>
    </div>
  </div>
</div>