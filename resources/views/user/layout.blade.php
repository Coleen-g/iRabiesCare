<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','User') - iRabiesCare</title>
  @vite(['resources/js/app.js', 'resources/css/app.css'])

  <!-- Professional Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    body {
      font-family: "Poppins", sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(120deg, #e8f5e9 0%, #f1f8e9 100%);
      color: #1a1a1a;
      min-height: 100vh;
    }

    .main-wrapper {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 230px;
      background: linear-gradient(135deg, #388e3c 0%, #43a047 100%);
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 2rem 1rem 1rem 1rem;
      box-shadow: 2px 0 12px rgba(56,142,60,0.10);
      position: sticky;
      top: 0;
      height: 100vh;
      z-index: 100;
    }
    .sidebar .brand {
      color: #fff;
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 2.5rem;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.7rem;
      flex-direction: column;
    }
    .sidebar .brand img {
      height: 80px; /* increased logo size */
      border-radius: 0; /* removed rounded edges */
      box-shadow: none; /* removed shadow */
    }
    .sidebar nav {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }
    .sidebar nav a {
      color: #c8e6c9;
      background: transparent;
      border-radius: 8px;
      padding: 0.7rem 1rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.7rem;
      text-decoration: none;
      transition: background 0.2s, color 0.2s, transform 0.2s;
      font-size: 1.08rem;
    }
    .sidebar nav a.active, .sidebar nav a:hover {
      background: #fff;
      color: #388e3c;
      font-weight: 700;
      transform: translateX(4px) scale(1.04);
      box-shadow: 0 2px 8px rgba(56,142,60,0.10);
    }
    .sidebar .avatar {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background: #fff;
      margin: 2rem 0 1rem 0;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px rgba(14,165,233,0.12);
      overflow: hidden;
    }
    .sidebar .avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
    }
    .sidebar .user-name {
      color: #fff;
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      text-align: center;
    }
    .sidebar .logout-btn {
      margin-top: 2rem;
      background: #fff;
      color: #388e3c;
      border: none;
      border-radius: 8px;
      padding: 0.6rem 1.2rem;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(56,142,60,0.10);
      transition: background 0.2s, color 0.2s, transform 0.2s;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .sidebar .logout-btn:hover {
      background: #388e3c;
      color: #fff;
      transform: scale(1.04);
    }

    /* Main Content */
    .main-content {
      flex: 1;
      padding: 0 0 0 0;
      min-width: 0;
      display: flex;
      flex-direction: column;
    }
    header {
      background: linear-gradient(90deg, #2e7d32 0%, #43a047 100%);
      color: #fff;
      box-shadow: 0 2px 10px rgba(56,142,60,0.10);
      padding: 0.7rem 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .header-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.5rem;
    }
    .header-title {
      font-size: 1.3rem;
      font-weight: 700;
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
      gap: 0.7rem;
      color: #fff;
      text-shadow: 0 2px 8px rgba(56,142,60,0.10);
    }
    .topbar-right {
      display: flex;
      align-items: center;
      gap: 1.2rem;
    }
    .topbar-right .muted {
      color: #c8e6c9;
      font-size: 1.05rem;
      display: flex;
      align-items: center;
      gap: .4rem;
      font-weight: 500;
    }
    .btn {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      padding: .45rem .9rem;
      border-radius: 6px;
      font-weight: 500;
      border: none;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.25s ease;
    }
    .btn-primary {
      background: linear-gradient(90deg, #43a047 0%, #a8e063 100%);
      color: #fff;
      font-weight: 700;
      box-shadow: 0 2px 8px rgba(67,160,71,0.08);
    }
    .btn-primary:hover {
      background: #43a047;
      color: #fff;
      transform: scale(1.05);
    }
    main {
      padding: 2.5rem 0 2rem 0;
      background: none;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1.5rem;
    }
    /* Responsive */
    @media (max-width: 1100px) {
      .container, .header-inner {
        max-width: 100vw;
        padding: 0 1rem;
      }
      .sidebar {
        width: 70px;
        padding: 1rem 0.2rem;
      }
      .sidebar .brand span, .sidebar .user-name {
        display: none;
      }
      .sidebar nav a {
        justify-content: center;
        font-size: 1.2rem;
        padding: 0.7rem 0.5rem;
      }
    }
    @media (max-width: 900px) {
      .main-wrapper {
        flex-direction: column;
      }
      .sidebar {
        flex-direction: row;
        width: 100vw;
        height: auto;
        position: static;
        box-shadow: none;
        padding: 0.5rem 0.5rem;
        justify-content: space-between;
      }
      .sidebar nav {
        flex-direction: row;
        gap: 0.2rem;
      }
      .sidebar .avatar {
        width: 40px;
        height: 40px;
        margin: 0 0.5rem 0 0;
      }
      .main-content {
        padding: 0;
      }
    }
    @media (max-width: 600px) {
      .container, .header-inner {
        padding: 0 0.2rem;
      }
      .sidebar {
        padding: 0.2rem 0.1rem;
      }
    }
  </style>
</head>

<body>
  <div class="main-wrapper">
    <aside class="sidebar">
      <a href="{{ route('user.dashboard') }}" class="brand">
        <img src="/images/logo.png" alt="iRabiesCare Logo" />
      </a>
      <div class="avatar">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(optional(optional(auth()->user())->patient)->name ?? auth()->user()->name) }}&background=0ea5e9&color=fff&size=128" alt="User Avatar" />
      </div>
      <div class="user-name">
        {{ optional(optional(auth()->user())->patient)->name ?? auth()->user()->name }}
      </div>
      <nav>
        <a href="{{ route('user.dashboard') }}" class="{{ request()->is('user/dashboard') ? 'active' : '' }}">
          <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>
        <a href="{{ route('user.cases') }}" class="{{ request()->is('user/cases*') ? 'active' : '' }}">
          <i class="bi bi-file-earmark-medical"></i> <span>Cases</span>
        </a>
        <a href="{{ route('user.vaccinations') }}" class="{{ request()->is('user/vaccinations*') ? 'active' : '' }}">
          <i class="bi bi-capsule-pill"></i> <span>Vaccinations</span>
        </a>
        <a href="{{ route('user.profile') }}" class="{{ request()->is('user/profile*') ? 'active' : '' }}">
          <i class="bi bi-person-circle"></i> <span>Profile</span>
        </a>
      </nav>
      <button id="logout-link" class="logout-btn">
        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
      </button>
    </aside>
    <div class="main-content">
      <header>
        <div class="header-inner">
          <div class="header-title">
            <i class="bi bi-heart-pulse-fill"></i> iRabiesCare User Portal
          </div>
          <div class="topbar-right">
            <div class="muted">
              <i class="bi bi-person-fill"></i>
              {{ optional(optional(auth()->user())->patient)->name ?? auth()->user()->name }}
            </div>
          </div>
        </div>
      </header>
      <main>
        <div class="container">
          @yield('content')
        </div>
      </main>
    </div>
  </div>

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
