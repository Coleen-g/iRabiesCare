<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','User') - iRabiesCare</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <style>
        body{font-family:Arial, Helvetica, sans-serif;margin:0;padding:0;background:#f8fafc}
        .container{max-width:1100px;margin:2rem auto;padding:1rem}
        .card{background:#fff;padding:1rem;border-radius:8px;box-shadow:0 4px 12px rgba(15,23,42,0.04)}
        .main-with-sidebar { margin-left: 260px; }
        .user-topbar { display:flex; align-items:center; gap:1rem; margin-bottom:1rem }
        .user-topbar .title { font-size:1.25rem; font-weight:700 }
        .user-actions { margin-left:auto }
        .btn { padding:.45rem .75rem; border-radius:6px; text-decoration:none }
        .btn-primary { background:#2563eb; color:#fff }
        .muted { color:#6b7280 }
    </style>
</head>
<body>
    <header style="background:#fff;border-bottom:1px solid #eef2f7;">
        <div class="container" style="display:flex;align-items:center;gap:1rem;padding:.75rem 1rem">
            <a href="{{ route('user.dashboard') }}" style="display:flex;align-items:center;gap:.75rem;text-decoration:none;color:inherit">
                <img src="/images/logoO.png" alt="logo" style="height:36px" />
                <strong style="font-size:1rem">iRabiesCare</strong>
            </a>

            <nav style="display:flex;gap:1rem;margin-left:1rem">
                <a href="{{ route('user.dashboard') }}" class="{{ request()->is('user/dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('user.cases') }}" class="{{ request()->is('user/cases*') ? 'active' : '' }}">Cases</a>
                <a href="{{ route('user.vaccinations') }}" class="{{ request()->is('user/vaccinations*') ? 'active' : '' }}">Vaccinations</a>
                <a href="{{ route('user.profile') }}" class="{{ request()->is('user/profile*') ? 'active' : '' }}">Profile</a>
            </nav>

            <div style="margin-left:auto;display:flex;align-items:center;gap:.5rem">
                <div class="muted">{{ optional(optional(auth()->user())->patient)->name ?? auth()->user()->name }}</div>
                <a href="#" id="logout-link" class="btn" style="text-decoration:none">Logout</a>
            </div>
        </div>
    </header>

    <main>
        <div class="container" style="padding:1.25rem 1rem">
            @yield('content')
        </div>
    </main>

    <script>
        (function(){
            const logoutLink = document.getElementById('logout-link');
            if(!logoutLink) return;
            logoutLink.addEventListener('click', function(e){
                e.preventDefault();
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch("{{ route('logout') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                }).finally(()=>{
                    window.location = "{{ route('login') }}";
                });
            });
        })();
    </script>
</body>
</html>