<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>iRabiesCare</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
