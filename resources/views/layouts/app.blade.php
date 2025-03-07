<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title-block')</title>
    <meta name="description" content="@yield('description-block')">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('custom-meta')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/css/style.css', 'resources/js/app.js', 'resources/js/script.js'])

</head>

<body>
    <div id="app">
        @include('inc.header')
        <main class="py-4">
            @include('inc.status')
            @include('inc.messages')
            @include('inc.toast')
            <div class="container-lg">
                <div class="row">
                    <div class="col">
                        @yield('breadcrumbs')
                    </div>
                </div>
            </div>
            @yield('content')
        </main>
        @include('inc.footer')
    </div>
</body>

</html>