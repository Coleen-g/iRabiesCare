<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','User') - iRabiesCare</title>
    <link rel="stylesheet" href="/css/app.css">
    <style>
        body{font-family:Arial, Helvetica, sans-serif;margin:0;padding:0}
        .container{max-width:900px;margin:2rem auto;padding:1rem}
        .nav{display:flex;gap:1rem;margin-bottom:1rem}
        .card{background:#fff;padding:1rem;border-radius:6px;box-shadow:0 1px 2px rgba(0,0,0,0.04)}
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="{{ route('user.dashboard') }}">Dashboard</a>
            <a href="{{ route('user.cases') }}">Cases</a>
            <a href="{{ route('user.vaccinations') }}">Vaccinations</a>
            <a href="{{ route('user.profile') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-left:auto">@csrf<button type="submit">Logout</button></form>
        </div>

        @yield('content')
    </div>
</body>
</html>