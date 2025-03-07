    <div class="card border-primary m-1">
      <div class="card-body p-1">
        <div class="card-text overflow-hidden text-nowrap">
          <a class="stretched-link text-decoration-none badge text-bg-light align-middle px-1"
            href="{{ route('cities.show', $city->id) }}">
            <span class="fs-6">
              {{ $city->name }}
              <small>({{ $city->users_count }})</small>
            </span>
          </a>
        </div>
      </div>
    </div>