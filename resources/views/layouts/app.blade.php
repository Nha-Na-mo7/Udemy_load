<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- タイトル -->
    <title>@yield('title') | UdemyLoad</title>
    <!-- キーワード -->
    <meta name="keyword" itemprop="keyword" content="@yield('keyword')">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

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
