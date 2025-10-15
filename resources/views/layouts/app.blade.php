<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'iRabiesCare')</title>
    @vite('resources/js/app.js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="min-h-screen">
        <div class="container mx-auto px-4">
            @yield('content')
        </div>
    </div>
</body>
</html>
