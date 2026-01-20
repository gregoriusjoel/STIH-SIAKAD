<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Absensi')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background:#f3f4f6; }
        .center-wrap { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:2rem; }
    </style>
</head>
<body>
    <div class="center-wrap">
        @yield('content')
    </div>
</body>
</html>