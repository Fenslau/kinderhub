<div class="row mb-3">
  <div class="d-flex justify-content-center flex-wrap">
    <div class="m-2">
      <a href="{{ route('login.redirect', 'driver=google') }}"><img style="max-height: 3rem" src="{{ asset('storage/icons/google.png') }}" alt="Google"> </a>
    </div>

    <div class="m-2">
      <a href="{{ route('login.redirect', 'driver=vkontakte') }}"><img style="max-height: 3rem" src="{{ asset('storage/icons/vk.png') }}" alt="VK"> </a>
    </div>

    <div class="m-2">
      <a href="{{ route('login.redirect', 'driver=odnoklassniki') }}"><img style="max-height: 3rem" src="{{ asset('storage/icons/ok.png') }}" alt="OK"> </a>
    </div>

    <div class="m-2">
      <a href="{{ route('login.redirect', 'driver=yandex') }}"><img style="max-height: 3rem" src="{{ asset('storage/icons/yandex.png') }}" alt="Yandex"> </a>
    </div>

    <div class="m-2">
      <a href="{{ route('login.redirect', 'driver=mailru') }}"><img style="max-height: 3rem" src="{{ asset('storage/icons/mailru.png') }}" alt="Mail.ru"> </a>
    </div>
  </div>
</div>