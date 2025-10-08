import React from "react";
import Navbar from "../Users/User_Navbar";
import User_Dashboard from "../Users/User_Dashboard";
import useAuth from "../../hooks/useAuth"; // 👈 import the new hook

export default function PatientDashboardPage() {
  const { user, loading, logout } = useAuth("/"); // Redirect unauthorized to homepage

  if (loading) return <div>Loading dashboard...</div>;
  if (!user) return null;

  return (
    <>
      <Navbar userName={user.name} onLogout={logout} />
      <User_Dashboard user={user} />
    </>
  );
}
