import React from 'react';
import '../../css/Sidebar.css';

export default function Sidebar({ active = 'dashboard', onLogout }) {
  return (
    <aside className="ir-sidebar">
      <div className="ir-sidebar-logo">
        <img src="/images/logoO.png" alt="iRabiesCare" />
      </div>

      <nav className="ir-nav">
        <a className={active === 'dashboard' ? 'active' : ''} href="/admin/dashboard">Dashboard</a>
        <a className={active === 'patients' ? 'active' : ''} href="/admin/patients">Patients</a>
        <a className={active === 'cases' ? 'active' : ''} href="/admin/cases">Cases</a>
        <a className={active === 'vaccinations' ? 'active' : ''} href="/admin/vaccinations">Vaccinations</a>
        <a className={active === 'reports' ? 'active' : ''} href="/admin/reports">Reports</a>
        <a className={active === 'settings' ? 'active' : ''} href="/admin/settings">Settings</a>
      </nav>

      <div className="ir-sidebar-footer">
        <button className="ir-logout" onClick={onLogout || (() => window.location = '/logout')}>Logout</button>
      </div>
    </aside>
  );
}
