// src/components/DashboardLayout.jsx
import { Outlet, useNavigate } from "react-router-dom";
import "../../css/Dashboard.css"; // ✅ keep CSS import

export default function DashboardLayout() {
  const navigate = useNavigate();

  return (
    <div className="dashboard-layout">
      {/* Sidebar */}
      <aside className="sidebar">
        {/* Logo */}
        <div
          className="sidebar-logo-container"
          onClick={() => navigate("/dashboard")}
          style={{ cursor: "pointer" }}
        >
          {/* ✅ Logo directly from public/images */}
          <img src="/images/logoO.png" alt="iRabiesCare Logo" />
        </div>

        <nav className="sidebar-nav">
          <button onClick={() => navigate("/admin")}>🏠 Dashboard</button>
          <button onClick={() => navigate("/admin/patients")}>👩‍⚕️ Patients</button>
          <button onClick={() => navigate("/admin/cases")}>🐾 Cases</button>
          <button onClick={() => navigate("/admin/vaccinations")}>💉 Vaccinations</button>
          <button onClick={() => navigate("/")}>🚪 Logout</button>
        </nav>
      </aside>

      {/* Main Content Area */}
      <main className="dashboard-content">
        <Outlet />
      </main>
    </div>
  );
}
