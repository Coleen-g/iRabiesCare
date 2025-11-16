<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - iRabiesCare</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Ensure bootstrap icon elements render even if other CSS overrides are present */
        .bi {
            font-family: "bootstrap-icons" !important;
            speak: none;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
    <!-- FontAwesome CDN for fa- icons -->
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
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            background: #fff;
            padding: 0.6rem 1rem;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            margin-bottom: 1.5rem;
        }

        .topbar-left {
            display:flex;
            align-items:center;
            gap:1rem;
        }

        .topbar-title {
            font-size:1.05rem;
            font-weight:700;
            margin:0;
            color:#111;
        }

        .topbar-search input {
            padding:0.45rem 0.6rem;
            border:1px solid #e6e6e6;
            border-radius:8px;
            min-width:260px;
            outline:none;
        }

        .topbar-right {
            display:flex;
            align-items:center;
            gap:0.75rem;
        }

        .topbar-right .notif {
            position:relative;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:40px;
            height:40px;
            border-radius:8px;
            color:#111;
            text-decoration:none;
        }

        .topbar-right .notif:hover { background:#f7f7f7 }

        .topbar-right .notif .badge {
            position:absolute;
            top:6px;
            right:6px;
            background:#ef4444;
            color:#fff;
            font-size:11px;
            padding:2px 6px;
            border-radius:999px;
            line-height:1;
        }

        .profile { display:flex; align-items:center; gap:0.5rem; padding:0.25rem 0.5rem; border-radius:6px }

        .profile .profile-name { font-weight:600 }

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

        /* Small inline compose icon placed next to signed-in name */
        .compose-inline {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:34px;
            height:34px;
            border-radius:6px;
            background:transparent;
            text-decoration:none;
            border:1px solid rgba(0,0,0,0.08);
        }

        .compose-inline:hover { background: #f0f0f0 }
        /* === Admin controls dropdown in topbar === */
        .admin-controls details {
            position: relative;
            display: inline-block;
        }

        .admin-controls summary {
            list-style: none;
            cursor: pointer;
            padding: 0.45rem 0.6rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: transparent;
            border: 1px solid transparent;
        }

        .admin-controls summary::-webkit-details-marker { display: none }

        .admin-controls[open] summary { background:#f7f7f7 }

        .admin-controls .dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            min-width: 220px;
            z-index: 60;
            overflow: hidden;
        }

        .admin-controls .dropdown a {
            display: block;
            padding: 0.6rem 0.9rem;
            color: #111;
            text-decoration: none;
            border-bottom: 1px solid #f2f2f2;
        }

        .admin-controls .dropdown a:hover { background:#f5f5f5 }
        /* === Sidebar Admin Controls (collapsible group) === */
        .ir-nav details.ir-admin-controls {
            margin: 0.4rem 0;
        }

        .ir-nav summary.ir-admin-summary {
            list-style: none;
            cursor: pointer;
            padding: 0.6rem 0.8rem;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.8rem;
            color: #d1d5db;
        }

        .ir-admin-summary .ir-admin-chevron {
            font-size: 0.95rem;
            color: #d1d5db;
            margin-left: 8px;
            transition: transform 0.18s ease;
        }

        .ir-admin-controls[open] .ir-admin-summary .ir-admin-chevron {
            transform: rotate(180deg);
            color: #111;
        }

        .ir-nav summary.ir-admin-summary:hover,
        .ir-nav details[open] summary.ir-admin-summary {
            background: #fff;
            color: #000;
            transform: translateX(6px);
        }

        .ir-nav .admin-group a {
            display: block;
            padding: 0.5rem 1.6rem;
            color: #6b7280; /* slightly darker gray for children */
            text-decoration: none;
            font-size: 0.92rem;
        }

        .ir-nav .admin-group a:hover { background: #f7f7f7; color: #111; }
        /* === Account controls (sidebar) reusing admin summary styles === */
        .ir-account-controls { margin: 0.4rem 0; }
        .ir-account-controls .account-group a { padding: 0.5rem 1.6rem; display:block; color:#6b7280; text-decoration:none }
        .ir-account-controls .account-group a:hover { background:#f7f7f7; color:#111 }
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
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </a>
                    {{-- Account controls (visible to authenticated users) --}}
                    @if(auth()->check())
                        <details class="ir-account-controls" @if(request()->routeIs('admin.profile*') || request()->routeIs('admin.profile.password') || request()->is('profile*')) open @endif>
                            <summary class="ir-admin-summary">
                                <i class="bi bi-person-circle"></i>
                                <span>Account</span>
                                <i class="bi bi-chevron-down ir-admin-chevron"></i>
                            </summary>
                            <div class="account-group">
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}"><i class="bi bi-person" style="margin-right:8px"></i> Profile</a>
                                    <a href="{{ route('admin.profile.password') }}" class="{{ request()->routeIs('admin.profile.password') ? 'active' : '' }}"><i class="bi bi-lock" style="margin-right:8px"></i> Change Password</a>
                                @else
                                    <a href="/profile" class="{{ request()->is('profile*') ? 'active' : '' }}"><i class="bi bi-person" style="margin-right:8px"></i> Profile</a>
                                    <a href="/profile/password" class="{{ request()->is('profile/password*') ? 'active' : '' }}"><i class="bi bi-lock" style="margin-right:8px"></i> Change Password</a>
                                @endif
                            </div>
                        </details>
                    @endif
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <details class="ir-admin-controls" @if(request()->routeIs('admin.patients*') || request()->routeIs('admin.health-staffs*') || request()->routeIs('admin.cases*') || request()->routeIs('admin.vaccinations*') || request()->routeIs('admin.generate-users*')) open @endif>
                            <summary class="ir-admin-summary">
                                <i class="bi bi-shield-lock"></i>
                                <span>Admin Controls</span>
                                <i class="bi bi-chevron-down ir-admin-chevron"></i>
                            </summary>
                            <div class="admin-group">
                                <a href="{{ route('admin.patients.index') }}" class="{{ request()->routeIs('admin.patients*') ? 'active' : '' }}"><i class="bi bi-people" style="margin-right:8px"></i> Patients</a>
                                <a href="{{ route('admin.health-staffs.index') }}" class="{{ request()->routeIs('admin.health-staffs*') ? 'active' : '' }}"><i class="bi bi-person-badge" style="margin-right:8px"></i> Health Staff</a>
                                <a href="{{ route('admin.cases.index') }}" class="{{ request()->routeIs('admin.cases*') ? 'active' : '' }}"><i class="bi bi-journal-medical" style="margin-right:8px"></i> Cases</a>
                                <a href="{{ route('admin.vaccinations.index') }}" class="{{ request()->routeIs('admin.vaccinations*') ? 'active' : '' }}"><i class="bi bi-capsule" style="margin-right:8px"></i> Vaccinations</a>
                                <a href="{{ route('admin.generate-users.preview') }}" class="{{ request()->routeIs('admin.generate-users*') ? 'active' : '' }}"><i class="bi bi-person-plus" style="margin-right:8px"></i> Generate Accounts</a>
                            </div>
                        </details>
                    @endif
                    <!-- Reports and Settings links removed per request -->
                  
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
                <div class="topbar-left">
                  
                   
                </div>

                <div class="topbar-right">
                    @if(auth()->check())
                        @php $currentUser = auth()->user(); @endphp
                        @if($currentUser && $currentUser->role === 'admin')
                            <a href="{{ route('admin.notifications.index') }}" class="notif" title="Notifications">
                        @elseif($currentUser && $currentUser->role === 'health_staff')
                            <a href="{{ route('health_staff.notifications.index') }}" class="notif" title="Notifications">
                        @else
                            <a href="{{ route('user.notifications.index') }}" class="notif" title="Notifications">
                        @endif
                            <i class="bi bi-bell" style="font-size:18px;"></i>
                            @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                            @if($unread)
                                <span class="badge">{{ $unread }}</span>
                            @endif
                        </a>
                    @endif

                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.messages.create') }}" class="compose-inline" title="Compose message">
                            <i class="bi bi-envelope-plus" style="font-size:16px;color:#000"></i>
                        </a>
                    @endif

                    {{-- Admin Controls moved to sidebar --}}

                    <div class="profile">
                        <i class="bi bi-person-circle" style="font-size:20px;color:#000"></i>
                        <div class="profile-name">{{ auth()->user()->name }}</div>
                    </div>
                </div>
            </div>

            @yield('content')
            {{-- compose button moved to topbar beside signed-in info --}}
        </main>
    </div>
</body>
</html>
