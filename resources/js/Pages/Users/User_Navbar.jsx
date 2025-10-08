import React, { useState, useEffect, useRef } from "react";
import { Link, useLocation } from "react-router-dom";
import "./css_user/User_Navbar.css";

function Navbar({ userName, onLogout }) {
  const location = useLocation();
  const [userMenuOpen, setUserMenuOpen] = useState(false);
  const dropdownRef = useRef(null);

  // Close dropdown when clicking outside
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setUserMenuOpen(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  return (
    <nav className="navbar">
      {/* Left: Logo + Navigation */}
      <div className="navbar-left">
        <Link to="/user/dashboard" className="navbar-logo">
          <img src="/logoW.png" alt="Logo" />
        </Link>

        <Link
          to="/user/dashboard"
          className={`nav-link ${
            location.pathname === "/user/dashboard" ? "active" : ""
          }`}
        >
          Dashboard
        </Link>

        <Link
          to="/records/case"
          className={`nav-link ${
            location.pathname === "/records/case" ? "active" : ""
          }`}
        >
          Case Records
        </Link>

        <Link
          to="/records/vaccine"
          className={`nav-link ${
            location.pathname === "/records/vaccine" ? "active" : ""
          }`}
        >
          Vaccine Records
        </Link>
      </div>

      {/* Right: Patient Dropdown */}
      <div className="dropdown user-menu" ref={dropdownRef}>
        <span
          className="nav-link user-name"
          onClick={() => setUserMenuOpen(!userMenuOpen)}
        >
          {userName || "Patient"} ▾
        </span>

        {userMenuOpen && (
          <div className="dropdown-menu right">
            <Link to="/profile" className="dropdown-item">
              Profile
            </Link>
            <button
              onClick={onLogout}
              className="dropdown-item logout-btn"
              style={{
                background: "none",
                border: "none",
                color: "inherit",
                textAlign: "left",
                cursor: "pointer",
                width: "100%",
              }}
            >
              Logout
            </button>
          </div>
        )}
      </div>
    </nav>
  );
}

export default Navbar;
