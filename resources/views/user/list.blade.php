    <div class="card border-primary m-1">
      <div class="card-body p-1">
        <div class="card-text overflow-hidden text-nowrap">
          @include('user.avatar', ['user' => $user])
          <a class="stretched-link text-decoration-none badge text-bg-light align-middle px-1"
            href="{{ route('users.show', $user->id) }}">
            <span class="fs-6">
              {{ $user->name }} <small>({{ $user->comments_count }})</small>
            </span>
          </a>
        </div>
      </div>
    </div>