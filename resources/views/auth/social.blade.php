<div class="row mb-3">
  <div class="d-flex justify-content-center flex-wrap">
    <div class="m-2">
      <a href="{{ route('login.redirect', 'driver=google') }}"><img style="max-height: 3rem" src="{{ asset('build/assets/icons/google.png') }}" alt="Google"> </a>
    </div>

    <div class="m-2">
      <a href="{{ route('login.redirect', 'driver=vkontakte') }}"><img style="max-height: 3rem" src="{{ asset('build/assets/icons/vk.png') }}" alt="VK"> </a>
    </div>

    <div class="m-2">
      <a href="{{ route('login.redirect', 'driver=odnoklassniki') }}"><img style="max-height: 3rem" src="{{ asset('build/assets/icons/ok.png') }}" alt="OK"> </a>
    </div>

    <div class="m-2">
      <a href="{{ route('login.redirect', 'driver=yandex') }}"><img style="max-height: 3rem" src="{{ asset('build/assets/icons/yandex.png') }}" alt="Yandex"> </a>
    </div>

    <div class="m-2">
      <a href="{{ route('login.redirect', 'driver=mailru') }}"><img style="max-height: 3rem" src="{{ asset('build/assets/icons/mailru.png') }}" alt="Mail.ru"> </a>
    </div>
  </div>
</div>