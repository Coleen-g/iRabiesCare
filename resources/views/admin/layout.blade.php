<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - iRabiesCare</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Choices.js for searchable selects -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Base */
        body {
            margin: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #f3f4f6;
            color: #1f2937;
        }

        .app {
            display: block;
            min-height: 100vh;
            background: #f9fafb;
        }

        /* Sidebar */
        .ir-sidebar {
            width: 240px;
            background: #1e293b;
            color: #f1f5f9;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: width 0.3s ease;
            padding: 1rem;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            overflow: auto;
        }

        .ir-sidebar-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .ir-sidebar-logo img {
            width: 80px;
            height: auto;
            border-radius: 50%;
        }

        .ir-nav {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .ir-nav a {
            color: #cbd5e1;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.55rem 0.75rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .ir-nav a i {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .ir-nav a.active, .ir-nav a:hover {
            background: #2563eb;
            color: #fff;
            transform: translateX(4px);
        }

        /* Logout button */
        .ir-sidebar-footer {
            margin-top: 2rem;
        }

        .ir-logout {
            width: 100%;
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 0.6rem 0;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s ease;
        }

        .ir-logout:hover {
            background: #dc2626;
        }

        /* Main Content */
        .content {
            margin-left: 240px; /* offset for fixed sidebar */
            padding: 1.5rem 2rem;
            background: #f9fafb;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background: #fff;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }

        .topbar strong {
            color: #1e3a8a;
        }

        /* Card */
        .card {
            background: #fff;
            padding: 1.25rem;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        /* ===== Compact admin table styles (used by patients/cases/vaccinations lists) ===== */
        table.admin-table { width:100%; border-collapse:collapse; font-size:13px; }
        table.admin-table th, table.admin-table td { padding:.45rem .5rem; text-align:left; border-bottom:1px solid #f3f4f6; vertical-align:middle }
        table.admin-table td { white-space:normal; word-break:break-word; max-width:240px }
        table.admin-table th[data-no-wrap], table.admin-table td[data-no-wrap] { white-space:nowrap; max-width:none }
        .search-input { padding:.45rem .6rem; border:1px solid #e5e7eb; border-radius:6px; width:220px }
        .btn-primary { background:#2563eb; color:#fff; padding:.45rem .6rem; border-radius:6px; text-decoration:none; font-size:13px }
        .btn-ghost { background:transparent; color:#374151; padding:.35rem .5rem; border-radius:6px; border:1px solid transparent; font-size:13px }
        .actions { display:flex; gap:.4rem; align-items:center }
        .action-edit { background:#f3f4f6; padding:.3rem .5rem; border-radius:6px; color:#111; text-decoration:none; font-size:13px }
        .action-delete { background:#fee2e2; padding:.3rem .5rem; border-radius:6px; color:#7f1d1d; border:0; font-size:13px }
        .notice { padding:.45rem; background:#ecfccb; border-radius:4px; margin-bottom:.6rem; font-size:13px }
        @media (max-width: 768px) {
            table.admin-table th, table.admin-table td { padding:.35rem .4rem; font-size:12px }
            .search-input { width:160px }
        }

        @media (max-width: 768px) {
            .ir-sidebar {
                width: 70px;
                align-items: center;
            }

            .ir-nav a {
                justify-content: center;
                font-size: 0;
            }

            .ir-nav a i {
                font-size: 1.2rem;
            }

            .ir-sidebar-logo img {
                width: 50px;
            }

            .content {
                margin-left: 70px; /* match collapsed sidebar */
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="app">
        <aside class="ir-sidebar">
            <div>
                <div class="ir-sidebar-logo">
                    <img src="/images/logoO.png" alt="iRabiesCare" />
                </div>

                <nav class="ir-nav">
                    <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </a>
                    <a href="/admin/patients" class="{{ request()->is('admin/patients*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> <span>Patients</span>
                    </a>
                    <a href="/admin/cases" class="{{ request()->is('admin/cases*') ? 'active' : '' }}">
                        <i class="bi bi-journal-medical"></i> <span>Cases</span>
                    </a>
                    <a href="/admin/vaccinations" class="{{ request()->is('admin/vaccinations*') ? 'active' : '' }}">
                        <i class="bi bi-capsule"></i> <span>Vaccinations</span>
                    </a>
                    <a href="/admin/reports" class="{{ request()->is('admin/reports*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line"></i> <span>Reports</span>
                    </a>
                    <a href="/admin/settings" class="{{ request()->is('admin/settings*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i> <span>Settings</span>
                    </a>
                    <a href="/admin/generate-users" class="{{ request()->is('admin/generate-users*') ? 'active' : '' }}">
                        <i class="bi bi-person-plus"></i> <span>Generate Accounts</span>
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
                <div><i class="bi bi-person-circle" style="color:#2563eb; margin-right:6px;"></i> Signed in as <strong>{{ auth()->user()->name }}</strong></div>
            </div>

            @yield('content')
        </main>
    </div>
</body>

<!-- Choices.js -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        // Non-AJAX selects
        document.querySelectorAll('select.searchable-patient-select:not([data-ajax-patient])').forEach(function(el){
            try { new Choices(el, { searchEnabled: true, itemSelectText: '' }); } catch(e) { console.warn('Choices init failed', e); }
        });

        // AJAX-enabled selects
        document.querySelectorAll('select.searchable-patient-select[data-ajax-patient]').forEach(function(el){
            try {
                const choices = new Choices(el, { searchEnabled: true, shouldSort: false, itemSelectText: '' });

                function debounce(fn, wait){ let t; return function(){ clearTimeout(t); t = setTimeout(()=>fn.apply(this, arguments), wait); }; }

                const fetchChoices = debounce(function(search){
                    const url = "{{ route('admin.patients.search') }}?q=" + encodeURIComponent(search || '');
                    fetch(url, { headers: { 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(data => {
                            choices.clearChoices();
                            choices.setChoices(data.map(d => ({ value: d.value, label: d.label })), 'value', 'label', true);
                        }).catch(err => console.warn('patient search failed', err));
                }, 300);

                fetchChoices('');
                el.addEventListener('search', e => fetchChoices(e.detail.value));
            } catch(e) { console.warn('Choices AJAX init failed', e); }
        });
    });
</script>
</html>
