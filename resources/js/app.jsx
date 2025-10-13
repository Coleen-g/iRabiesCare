import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";

// Context
import { AuthProvider, useAuth } from "./contexts/AuthContext";

// Pages
import DashboardLayout from "./Pages/DashboardLayout";
import Admin_Dashboard from "./Pages/Admin/Admin_Dashboard";
import Admin_Patients from "./Pages/Admin/Admin_Patients";
import Admin_Cases from "./Pages/Admin/Admin_Cases";
import Admin_Vaccinations from "./Pages/Admin/Admin_Vaccinations";
import Health_Staff_Dashboard from "./Pages/Health_Staff/Health_Staff_Dashboard";
import Health_Staff_Patients from "./Pages/Health_Staff/Health_Staff_Patients";
import Health_Staff_Cases from "./Pages/Health_Staff/Health_Staff_Cases";
import Health_Staff_Vaccinations from "./Pages/Health_Staff/Health_Staff_Vaccinations";
import PatientDashboardPage from "./Pages/Users/PatientDashboardPage";
import LoginPage from "./Pages/Auth/LoginPage";
import RegisterPage from "./Pages/Auth/RegisterPage";

// Protected Route
import ProtectedRoute from "./components/ProtectedRoute";

function AppRoutes() {
  const { user, loading } = useAuth();

  if (loading) return <p>Loading...</p>;

  return (
    <Routes>
      {/* Auth */}
      <Route path="/" element={<LoginPage />} />
      <Route path="/register" element={<RegisterPage />} />

      {/* Admin */}
      <Route
        path="/admin/*"
        element={
          <ProtectedRoute role="admin">
            <DashboardLayout />
          </ProtectedRoute>
        }
      >
        <Route index element={<Admin_Dashboard />} />
        <Route path="patients" element={<Admin_Patients />} />
        <Route path="cases" element={<Admin_Cases />} />
        <Route path="vaccinations" element={<Admin_Vaccinations />} />
      </Route>

      {/* Health Staff */}
      <Route
        path="/staff/*"
        element={
          <ProtectedRoute role="health_staff">
            <DashboardLayout />
          </ProtectedRoute>
        }
      >
        <Route index element={<Health_Staff_Dashboard />} />
        <Route path="patients" element={<Health_Staff_Patients />} />
        <Route path="cases" element={<Health_Staff_Cases />} />
        <Route path="vaccinations" element={<Health_Staff_Vaccinations />} />
      </Route>

      {/* Patient */}
      <Route
        path="/patient/*"
        element={
          <ProtectedRoute role="patient">
            <PatientDashboardPage />
          </ProtectedRoute>
        }
      />

      {/* Fallback */}
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}

function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <AppRoutes />
      </AuthProvider>
    </BrowserRouter>
  );
}

ReactDOM.createRoot(document.getElementById("app")).render(<App />);
