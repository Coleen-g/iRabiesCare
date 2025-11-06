<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Health Staff') - iRabiesCare</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome CDN for fa- icons (match admin layout) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKB4Imkb9hFQ9U1FVLtZL1YI7Di5urN6pN1Nsx3Rp3XIan+FJxux1DPZWS9Yuk3F7S3w7DtwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* === Base === */
        body {
            margin: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #f5f5f5;
            color: #111;
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        /* === Sidebar === */
        .ir-sidebar {
            width: 240px;
            background: #000; /* Pure black */
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            padding: 1.5rem 1rem;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.4);
        }

        .ir-sidebar-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .ir-sidebar-logo img {
            width: 140px; /* bigger logo */
            height: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        /* Navigation */
        .ir-nav {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            flex-grow: 1;
        }

        .ir-nav a {
            color: #d1d5db; /* light gray text */
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.6rem 0.8rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .ir-nav a i {
            font-size: 1.1rem;
        }

        .ir-nav a:hover,
        .ir-nav a.active {
            background: #fff;
            color: #000;
            transform: translateX(6px);
        }

        /* === Logout Button === */
        .ir-sidebar-footer {
            margin-top: 1.5rem;
        }

        .ir-logout {
            width: 100%;
            background: #fff;
            color: #000;
            border: none;
            padding: 0.65rem 0;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: background 0.25s ease, transform 0.2s ease;
        }

        .ir-logout:hover {
            background: #e5e5e5;
            transform: scale(1.03);
        }

        /* === Main Content === */
        .content {
            margin-left: 240px;
            padding: 1.5rem 2rem;
            background: #f5f5f5;
            min-height: 100vh;
            color: #111;
            flex: 1;
            width: calc(100% - 240px);
            box-sizing: border-box;
        }

        /* === Topbar === */
        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background: #fff;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }

        .topbar strong {
            color: #000;
        }

        /* === Card === */
        .card {
            background: #fff;
            padding: 1.25rem;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        /* === Table === */
        table.admin-table { width:100%; border-collapse:collapse; font-size:13px; color:#111 }
        table.admin-table th, table.admin-table td { padding:.45rem .5rem; text-align:left; border-bottom:1px solid #eee; vertical-align:middle }
        table.admin-table td { white-space:normal; word-break:break-word; max-width:240px }
        table.admin-table th[data-no-wrap], table.admin-table td[data-no-wrap] { white-space:nowrap; max-width:none }
        .search-input { padding:.45rem .6rem; border:1px solid #ccc; border-radius:6px; width:220px }
        .btn-primary { background:#000; color:#fff; padding:.45rem .6rem; border-radius:6px; text-decoration:none; font-size:13px }
        .btn-primary:hover { background:#222 }
        .btn-ghost { background:transparent; color:#000; padding:.35rem .5rem; border:1px solid transparent; font-size:13px }
        .actions { display:flex; gap:.4rem; align-items:center }
        .action-edit { background:#f5f5f5; padding:.3rem .5rem; border-radius:6px; color:#111; text-decoration:none; font-size:13px }
        .action-delete { background:#f8d7da; padding:.3rem .5rem; border-radius:6px; color:#721c24; border:0; font-size:13px }
        .notice { padding:.45rem; background:#f0f0f0; border-radius:4px; margin-bottom:.6rem; font-size:13px }

        /* === Responsive === */
        @media (max-width: 768px) {
            .ir-sidebar {
                width: 70px;
                align-items: center;
                padding: 1rem 0.5rem;
            }

            .ir-nav a {
                justify-content: center;
                font-size: 0;
                padding: 0.7rem;
            }

            .ir-nav a span {
                display: none;
            }

            .ir-sidebar-logo img {
                width: 50px;
            }

            .content {
                margin-left: 70px;
                padding: 1rem;
                width: calc(100% - 70px);
            }
        }
    </style>
</head>
<body>
    <div class="app">
        <aside class="ir-sidebar">
            <div>
                <div class="ir-sidebar-logo">
                    <img src="/images/logo.png" alt="iRabiesCare" />
                </div>

                <nav class="ir-nav">
                    <a href="/health_staff/dashboard" class="{{ request()->is('health_staff/dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </a>
                    <a href="/health_staff/patients" class="{{ request()->is('health_staff/patients*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> <span>Patients</span>
                    </a>
                    <a href="/health_staff/cases" class="{{ request()->is('health_staff/cases*') ? 'active' : '' }}">
                        <i class="bi bi-journal-medical"></i> <span>Cases</span>
                    </a>
                    <a href="/health_staff/vaccinations" class="{{ request()->is('health_staff/vaccinations*') ? 'active' : '' }}">
                        <i class="bi bi-capsule"></i> <span>Vaccinations</span>
                    </a>
                </nav>
            </div>

            <div class="ir-sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="ir-logout" type="submit">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="content">
            <div class="topbar">
                <div style="display:flex;align-items:center;gap:1rem">
                    <div style="position:relative">
                        <a href="{{ route('health_staff.notifications.index') }}" title="Notifications" style="color:#000;text-decoration:none">
                            <i class="bi bi-bell" style="font-size:1.25rem"></i>
                        </a>
                        @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                        @if($unread)
                            <span style="position:absolute;top:-6px;right:-8px;background:#ef4444;color:#fff;border-radius:999px;padding:2px 6px;font-size:11px">{{ $unread }}</span>
                        @endif
                    </div>
                    <div><i class="bi bi-person-circle" style="color:#000; margin-right:6px;"></i> Signed in as <strong>{{ auth()->user()->name }}</strong></div>
                </div>
            </div>

            @yield('content')
        </main>
    </div>
</body>
</html>
...existing code...