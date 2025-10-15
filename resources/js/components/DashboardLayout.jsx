import React from 'react';
import { Outlet, useNavigate } from "react-router-dom";
import "../../css/Dashboard.css";

export default function DashboardLayout() {
  const navigate = useNavigate();

  return (
    <div className="dashboard-layout">
      <aside className="sidebar">
        <div
          className="sidebar-logo-container"
          onClick={() => navigate("/admin")}
          style={{ cursor: "pointer" }}
        >
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

      <main className="dashboard-content">
        <Outlet />
      </main>
    </div>
  );
}
