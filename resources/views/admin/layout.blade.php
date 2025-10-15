<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - iRabiesCare</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <style>
        body { margin:0; font-family: Arial, Helvetica, sans-serif; }
        .app { display:flex; min-height:100vh; }
        .sidebar { width:220px; background:#1f2937; color:#fff; padding:1rem; }
        .sidebar a { color:#cbd5e1; text-decoration:none; display:block; padding:.5rem .75rem; border-radius:4px; }
        .sidebar a.active, .sidebar a:hover { background:#374151; color:#fff; }
        .brand { font-weight:700; margin-bottom:1rem; font-size:1.1rem; }
        .content { flex:1; padding:1.25rem; background:#f3f4f6; }
        .topbar { display:flex; justify-content:flex-end; margin-bottom:1rem; }
        .card { background:#fff; padding:1rem; border-radius:6px; box-shadow:0 1px 2px rgba(0,0,0,0.04); }
    </style>
</head>
<body>
    <div class="app">
        <aside class="ir-sidebar">
            <div class="ir-sidebar-logo">
                <img src="/images/logoO.png" alt="iRabiesCare" />
            </div>

            <nav class="ir-nav">
                <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="/admin/patients" class="{{ request()->is('admin/patients*') ? 'active' : '' }}">Patients</a>
                <a href="/admin/cases" class="{{ request()->is('admin/cases*') ? 'active' : '' }}">Cases</a>
                <a href="/admin/vaccinations" class="{{ request()->is('admin/vaccinations*') ? 'active' : '' }}">Vaccinations</a>
                <a href="/admin/reports" class="{{ request()->is('admin/reports*') ? 'active' : '' }}">Reports</a>
                <a href="/admin/settings" class="{{ request()->is('admin/settings*') ? 'active' : '' }}">Settings</a>
            </nav>

            <div class="ir-sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="ir-logout" type="submit">Logout</button>
                </form>
            </div>
        </aside>

        <main class="content">
            <div class="topbar">
                <div>Signed in as <strong>{{ auth()->user()->name }}</strong></div>
            </div>

            @yield('content')
        </main>
    </div>
</body>
</html>