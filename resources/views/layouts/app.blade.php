<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sevia')</title>
    @vite(['packages/frontend-sevia/resources/css/app.css', 'packages/frontend-sevia/resources/js/app.js'], 'build/frontend-sevia')
</head>
<body>
    @yield('content')
</body>
</html>