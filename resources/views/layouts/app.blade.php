<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- title -->
    <title>@yield('title') | UdemyLoad</title>
    <!-- keywords -->
    <meta name="keyword" itemprop="keyword" content="@yield('keyword')">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

    {{-- flash message --}}
    @if(Session::has('session_msg'))
        <div class="c-flash js-flash-system-message" role="alert">
            <p>{{ session('session_msg') }}</p>
        </div>
    @endif

    {{-- flash success message --}}
    @if(Session::has('session_success'))
        <div class="c-flash c-flash__success js-flash-system-message" role="alert">
            <p>{{ session('session_success') }}</p>
        </div>
    @endif

    {{-- flash error message --}}
    @if(Session::has('session_error'))
        <div class="c-flash c-flash__error js-flash-system-message" role="alert">
            <p>{{ session('session_error') }}</p>
        </div>
    @endif

    {{-- メインコンテンツ --}}
    <main class="l-container">
        @yield('content')
    </main>

    {{-- フッター --}}
    <footer id="footer" class="l-footer">
        @include('layouts.footer')
    </footer>
</body>
</html>
