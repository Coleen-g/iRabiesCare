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
  background: #f6f8f7;
  color: #1f2937;
  min-height: 100vh;
}

/* LAYOUT */
.main-wrapper {
  display: flex;
  min-height: 100vh;
}

/* ===== SIDEBAR ===== */
.sidebar {
  width: 250px;
  background: linear-gradient(180deg, #256e40 0%, #1b5e20 100%);
  color: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 2rem 1rem;
  border-right: 1px solid rgba(255,255,255,0.08);
  box-shadow: 4px 0 12px rgba(0,0,0,0.05);
  position: sticky;
  top: 0;
  height: 100vh;
  z-index: 100;
  transition: all 0.3s ease;
}

.sidebar .brand {
  color: #fff;
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 2.5rem;
  text-decoration: none;
  text-align: center;
}

.sidebar .brand img {
  height: 70px;
  margin-bottom: 0.4rem;
}

.sidebar .avatar {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid rgba(255,255,255,0.3);
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  margin-bottom: 0.8rem;
}
.sidebar .avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.sidebar .user-name {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  text-align: center;
}

.sidebar nav {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}
.sidebar nav a {
  color: rgba(255,255,255,0.8);
  background: transparent;
  border-radius: 10px;
  padding: 0.75rem 1rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 0.7rem;
  text-decoration: none;
  transition: all 0.25s ease;
  font-size: 1.05rem;
}
.sidebar nav a:hover, .sidebar nav a.active {
  background: rgba(255,255,255,0.95);
  color: #256e40;
  transform: translateX(6px);
  font-weight: 600;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* LOGOUT */
.logout-btn {
  margin-top: auto;
  background: #f9fafb;
  color: #256e40;
  border: none;
  border-radius: 8px;
  padding: 0.7rem 1rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.25s ease;
}
.logout-btn:hover {
  background: #256e40;
  color: #fff;
  transform: scale(1.05);
}

/* ===== MAIN CONTENT ===== */
.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

/* HEADER */
header {
  background: rgba(255,255,255,0.85);
  backdrop-filter: blur(8px);
  border-bottom: 1px solid #e5e7eb;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  padding: 0.8rem 1.5rem;
  position: sticky;
  top: 0;
  z-index: 999;
}
.header-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: 1200px;
  margin: 0 auto;
}
.header-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1b5e20;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.header-title i {
  color: #2e7d32;
}
.topbar-right {
  display: flex;
  align-items: center;
  gap: 1.2rem;
}
.topbar-right .muted {
  color: #374151;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
  gap: .4rem;
  font-weight: 500;
}
.topbar-right a i {
  color: #256e40;
  font-size: 1.25rem;
  transition: color 0.2s ease;
}
.topbar-right a:hover i {
  color: #388e3c;
}

/* CONTAINER */
main {
  padding: 2rem 1.5rem;
}
.container {
  max-width: 1200px;
  margin: 0 auto;
}

/* RESPONSIVE */
@media (max-width: 1000px) {
  .sidebar {
    width: 80px;
    padding: 1rem 0.5rem;
  }
  .sidebar .brand, .sidebar .user-name, .sidebar nav a span {
    display: none;
  }
  .sidebar nav a {
    justify-content: center;
  }
}
@media (max-width: 768px) {
  .main-wrapper {
    flex-direction: column;
  }
  .sidebar {
    flex-direction: row;
    width: 100%;
    height: auto;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  .sidebar nav {
    flex-direction: row;
    justify-content: center;
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
            <div style="position:relative">
                <a href="{{ route('user.notifications.index') }}" title="Notifications" style="color:#fff;text-decoration:none">
                    <i class="bi bi-bell" style="font-size:1.25rem"></i>
                </a>
                @php $unreadUser = auth()->user()->unreadNotifications()->count(); @endphp
                @if($unreadUser)
                    <span style="position:absolute;top:-6px;right:-10px;background:#ef4444;color:#fff;border-radius:999px;padding:2px 6px;font-size:11px">{{ $unreadUser }}</span>
                @endif
            </div>

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
