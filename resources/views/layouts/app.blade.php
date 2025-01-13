<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/css/style.css', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        @include('inc.header')
        <main class="py-4">
            @include('inc.status')
            @include('inc.messages')
            @include('inc.toast')
            @yield('content')
        </main>
        @include('inc.footer')
    </div>
</body>

</html>