<header>
  <nav class="navbar navbar-expand-md navbar-light bg-primary shadow-sm" data-bs-theme="dark">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
        <i class="fa fa-home" aria-hidden="true"></i>{{ config('app.name', 'Laravel') }}
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav me-auto" data-bs-theme="light">
          @include('inc.menu')
        </ul>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ms-auto">
          <li data-bs-theme="light">
            @include('inc.search-form')
          </li>
          <!-- Authentication Links -->
          @guest
          @if (Route::has('login'))
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">
              <i class="fa fa-sign-in" aria-hidden="true"></i> {{ __('Login') }}</a>
          </li>
          @endif

          @if (Route::has('register'))
          <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">
              <i class="fa fa-user-plus" aria-hidden="true"></i> {{ __('Register') }}</a>
          </li>
          @endif
          @else
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle p-0" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
              @include('user.avatar', ['user' => $authUser, 'maxHeight' => '2.5', 'xHeight' => 2])
              {{ Str::limit($authUser->name, 20) }}
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" data-bs-theme="light">
              @if(!$authUser->hasVerifiedEmail())
              <a class="dropdown-item" href="{{ route('verification.notice') }}">
                <i class="fa fa-envelope" aria-hidden="true"></i> Подтвердить email
              </a>
              @endif
              @if($authUser->isAdmin())
              <a class="dropdown-item" href="{{ route('filament.admin.pages.dashboard') }}">
                <i class="fa fa-cog" aria-hidden="true"></i> Админка
              </a>
              @endif
              <a href="{{ route('filament.admin.auth.profile') }}"
                @class(['dropdown-item', 'disabled'=> !$authUser->isActive()])>
                <i class="fa fa-user" aria-hidden="true"></i> Профиль
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
              <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out" aria-hidden="true"></i> {{ __('Logout') }}
              </a>

              <script>
                function toggleTheme() {
                  const html = document.documentElement;
                  const currentTheme = html.getAttribute('data-bs-theme');
                  html.setAttribute('data-bs-theme', currentTheme === 'dark' ? 'light' : 'dark');
                }
              </script>
              <a class="dropdown-item" href=""
                onclick="event.preventDefault(); toggleTheme()">
                <i class="fa fa-lightbulb-o" aria-hidden="true"></i> Переключить тему
              </a>

            </div>
          </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>
</header>