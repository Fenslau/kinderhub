<div class="row g-4">
  @foreach ($announcements as $announcement)
  <div class="w-100">
    <div class="card h-100">
      <div class="card-header position-relative">
        <a class="nav-link pb-0 d-flex justify-content-between" aria-current="true" href="{{ route('announcements.show', $announcement->slug) }}">
          <h2 class="my-0 position-relative">{{ $announcement->title }}
          </h2>
          <div class="d-flex justify-content-around flex-column align-items-end">
            @if($announcement->isGlobal())
            <span class="opacity-75 top-0 end-0 badge rounded-pill text-bg-secondary"
              data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Закреплено">
              <i class="fa fa-thumb-tack" aria-hidden="true"></i>
            </span>
            @endif
            <span @class([ "text-bg-{$announcement->type->getColor()}" , "align-items-end opacity-75 bottom-0 end-0 badge rounded-pill" ,
              ])>
              {{ $announcement->type->getLabel() }}
            </span>
          </div>
        </a>
      </div>
      <div class="card-body">
        <div class="card-text d-flex justify-content-between align-items-baseline">
          <div>
            <a class="text-decoration-none" @empty($announcement->user?->id) @else href="{{ route('users.show', $announcement->user?->id ?? '') }}" @endempty>
              @include('user.avatar', ['user' => $announcement->user])
              {{ $announcement->user->name }}
            </a>
          </div>
          <div>
            <small class="card-text text-muted">{{ \Carbon\Carbon::parse($announcement->created_at)->diffForHumans() }}
            </small>
          </div>
        </div>
      </div>

      <div style="max-height:400px;" class="card-body position-relative overflow-hidden">
        <div class="card-text position-relative overflow-hidden">
          @empty($announcement->highlights)
          {!! $announcement->content !!}
          @else
          {!! $announcement->highlights !!}
          @endempty
        </div>
      </div>

      @if(!empty($announcement->sub_category))
      <ul class="list-group list-group-flush border">
        <li class="list-group-item text-muted small">
          @include('announcement.categories')
        </li>
      </ul>
      @endif

      <div class="card-footer">
        <div class="d-flex justify-content-between">
          <div class="">

          </div>

          <a href="{{ route('announcements.show', $announcement->slug) }}" class="card-link">Посмотреть</a>
        </div>

      </div>
    </div>
  </div>
  @endforeach
</div>
<div class="my-3">
  @if($announcements instanceof \Illuminate\Pagination\LengthAwarePaginator)
  {{ $announcements->links() }}
  @endif
</div>