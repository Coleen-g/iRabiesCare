import React from "react";
import "./css_user/User_Profile.css";

function User_Profile() {
  return (
    <div className="profile-container">
      <h2 className="profile-title">Patient Profile</h2>

      {/* Patient Information */}
      <div className="profile-card">
        <h3>Patient Information</h3>
        <p><strong>Full Name:</strong> Juan Dela Cruz</p>
        <p><strong>Patient ID:</strong> RBS-2025-001</p>
        <p><strong>Age:</strong> 34</p>
        <p><strong>Gender:</strong> Male</p>
        <p><strong>Contact Number:</strong> 09123456789</p>
      </div>

      {/* Case Information */}
      <div className="profile-card">
        <h3>Case Information</h3>
        <p><strong>Case Number:</strong> RC-00123</p>
        <p><strong>Exposure Type:</strong> Dog Bite - Category III</p>
        <p><strong>Exposure Date:</strong> Sept 10, 2025</p>
        <p><strong>Wound Location:</strong> Left Arm</p>
        <p><strong>Status:</strong> Undergoing Treatment</p>
      </div>

      {/* Vaccination Record */}
      <div className="profile-card">
        <h3>Vaccination Record</h3>
        <p><strong>Vaccine:</strong> Verorab</p>
        <p><strong>Dose 1:</strong> Sept 10, 2025</p>
        <p><strong>Dose 2:</strong> Sept 13, 2025</p>
        <p><strong>Dose 3:</strong> Sept 17, 2025</p>
        <p><strong>Next Dose:</strong> Sept 24, 2025</p>
      </div>

      {/* Update Password */}
      <div className="profile-card">
        <h3>Update Password</h3>
        <form>
          <label>Current Password</label>
          <input type="password" placeholder="Enter current password" />

          <label>New Password</label>
          <input type="password" placeholder="Enter new password" />

          <label>Confirm Password</label>
          <input type="password" placeholder="Confirm new password" />

          <button type="submit">SAVE</button>
        </form>
      </div>
    </div>
  );
}
export default User_Profile;
