<li class="nav-item">
    <a class="nav-link" href="{{ route('articles.index') }}">Статьи</a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle"
        data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true"
        aria-expanded="false">Объявления</a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('announcements.index') }}">Все</a>
        <div class="dropdown-divider"></div>
        @foreach(\App\Models\CareCategory::all() as $category)
        <a class="dropdown-item"
            href="{{ route('announcements.index', ['category' => $category->title]) }}">
            {{ $category->title }}
        </a>
        @endforeach
    </div>
</li>