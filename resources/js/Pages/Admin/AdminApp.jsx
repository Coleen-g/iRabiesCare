import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import DashboardLayout from '../../components/DashboardLayout.jsx';
import '../../../css/AdminApp.css';

function AdminHome() {
  return (
    <div className="admin-home card">
      <h1>Admin Dashboard (client)</h1>
      <p>Welcome to the admin dashboard.</p>
    </div>
  );
}

export default function AdminApp() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/admin" element={<DashboardLayout />}>
          <Route index element={<AdminHome />} />
          <Route path="dashboard" element={<AdminHome />} />
          <Route path="patients" element={<div className="card">Patients</div>} />
          <Route path="cases" element={<div className="card">Cases</div>} />
          <Route path="vaccinations" element={<div className="card">Vaccinations</div>} />
          {/* catch-all: render AdminHome for unknown admin paths */}
          <Route path="*" element={<AdminHome />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}
