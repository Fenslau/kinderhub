<li class="nav-item">
    <a class="nav-link" href="{{ route('articles.index') }}">Статьи</a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle"
        data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true"
        aria-expanded="false">Объявления</a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('announcements.index'                                                               ) }}">Все</a>
        <div class="dropdown-divider"></div>
        @foreach(\App\Models\CareCategory::where('parent_id', -1)->get() as $careCategory)
        <a class="dropdown-item"
            href="{{ route('announcements.index', ['care_category' => $careCategory->title]) }}">
            {{ $careCategory->title }}
        </a>
        @endforeach
    </div>
</li>