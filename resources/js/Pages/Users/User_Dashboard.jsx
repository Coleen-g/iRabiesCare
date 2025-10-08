import React from "react";
import "./css_user/User_Dash.css";

export default function User_Dashboard({ user }) {
  if (!user) return <p>Loading user data...</p>;

  const announcements = [
    {
      title: "Free Anti-Rabies Vaccination",
      date: "10/01/2025, 9:00 AM",
      details: "Free vaccination drive will be held at the City Health Office.",
      postedBy: "Admin",
    },
    {
      title: "Case Monitoring Update",
      date: "09/25/2025, 3:30 PM",
      details: "Please update your latest follow-up consultation details.",
      postedBy: "System Administrator",
    },
  ];

  return (
    <div className="dashboard-container">
      {/* Welcome Banner */}
      <div className="welcome-banner">
        <h1>
          Welcome back, <span>{user?.name}</span>!
        </h1>
        <p>Here’s an overview of your case and vaccination status.</p>
      </div>

      <div className="dashboard-grid">
        {/* User Info Card */}
        <section className="card info-card">
          <h2>Patient Information</h2>
          <table className="info-table">
            <tbody>
              <tr>
                <th>ID Number</th>
                <td>{user?.id}</td>
              </tr>
              <tr>
                <th>Email</th>
                <td>{user?.email}</td>
              </tr>
              <tr>
                <th>Status</th>
                <td>Active Patient</td>
              </tr>
              <tr>
                <th>Vaccine Status</th>
                <td>Ongoing Vaccination</td>
              </tr>
              <tr>
                <th>Department</th>
                <td>Rabies Case Management</td>
              </tr>
            </tbody>
          </table>
        </section>

        {/* Announcements */}
        <section className="card announcement-card">
          <h2>Latest Announcements</h2>
          {announcements.map((a, index) => (
            <div key={index} className="announcement-item">
              <h3>{a.title}</h3>
              <p className="date">{a.date}</p>
              <p>{a.details}</p>
              <p className="posted">Posted by: {a.postedBy}</p>
            </div>
          ))}
        </section>
      </div>
    </div>
  );
}
