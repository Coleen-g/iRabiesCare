<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','User') - iRabiesCare</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <!-- Add professional icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            font-family: "Inter", Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8fafc;
            color: #111827;
        }

        header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* ─── HEADER ───────────────────────────── */
        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }

        /* ─── NAVBAR ───────────────────────────── */
        nav {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: 2rem;
        }

        nav a {
            display: flex;
            align-items: center;
            gap: .4rem;
            color: #374151;
            font-weight: 500;
            text-decoration: none;
            padding: .4rem .7rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        nav a:hover {
            background: #eff6ff;
            color: #2563eb;
        }

        nav a.active {
            background: #2563eb;
            color: #fff;
        }

        /* ─── TOPBAR RIGHT ────────────────────── */
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-right .muted {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .btn {
            padding: .45rem .85rem;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
            border: none;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        main {
            padding: 1.5rem 0;
            /* ensure content doesn't sit under the sticky header */
            margin-top: 0;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
            padding: 1rem 1.25rem;
        }

        .muted { color: #6b7280; }

        /* Responsive Layout */
        @media (max-width: 900px) {
            nav {
                display: none;
            }

            .brand strong {
                font-size: 1rem;
            }

            .topbar-right {
                flex-direction: column;
                align-items: flex-end;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-inner">
            <div style="display:flex;align-items:center;">
                <a href="{{ route('user.dashboard') }}" class="brand">
                    <img src="/images/logoO.png" alt="logo" style="height:58px;" />
                </a>

                <nav>
                    <a href="{{ route('user.dashboard') }}" class="{{ request()->is('user/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('user.cases') }}" class="{{ request()->is('user/cases*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-medical"></i> Cases
                    </a>
                    <a href="{{ route('user.vaccinations') }}" class="{{ request()->is('user/vaccinations*') ? 'active' : '' }}">
                        <i class="bi bi-capsule-pill"></i> Vaccinations
                    </a>
                    <a href="{{ route('user.profile') }}" class="{{ request()->is('user/profile*') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i> Profile
                    </a>
                </nav>
            </div>

            <div class="topbar-right">
                <div class="muted">
                    <i class="bi bi-person"></i>
                    {{ optional(optional(auth()->user())->patient)->name ?? auth()->user()->name }}
                </div>
                <a href="#" id="logout-link" class="btn btn-primary">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
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
                }).finally(()=> window.location = "{{ route('login') }}");
            });
        })();
    </script>
</body>
</html>
