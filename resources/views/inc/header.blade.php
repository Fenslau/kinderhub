<header>
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
        <i class="fa fa-home" aria-hidden="true"></i>{{ config('app.name', 'Laravel') }}
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav me-auto">

        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ms-auto">
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
              @if(!empty(Auth::user()->profile->image))
              <img src="{{ filter_var(Auth::user()->profile->image, FILTER_VALIDATE_URL) 
                ? Auth::user()->profile->image 
                : Storage::url(Auth::user()->profile->image) }}"
                style="height: 2.5rem; width: 2.5rem;"
                class="rounded-circle">
              @endif
              {{ Auth::user()->name }}
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              @if(auth()->user()->isAdmin())
              <a class="dropdown-item" href="{{ route('filament.admin.pages.dashboard') }}">
                <i class="fa fa-cog" aria-hidden="true"></i> Админка
              </a>
              @endif
              <a class="dropdown-item" href="{{ route('filament.admin.resources.users.edit', auth()->user()->id) }}">
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
            </div>
          </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>
</header>