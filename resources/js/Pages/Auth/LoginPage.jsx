// src/Pages/Auth/LoginPage.jsx
import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../contexts/AuthContext"; // ✅ AuthContext
import "../../../css/LoginPage.css";

export default function LoginPage() {
  const navigate = useNavigate();
  const { login } = useAuth();
  const [formData, setFormData] = useState({ email: "", password: "" });
  const [error, setError] = useState("");

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleLogin = async (e) => {
    e.preventDefault();
    setError("");

    try {
      await login(formData.email, formData.password);
      // login handles redirect based on role
    } catch (err) {
      console.error("Login error:", err);

      if (err.response?.status === 419) {
        setError("Session expired or CSRF mismatch. Please refresh and try again.");
      } else if (err.response?.data?.message) {
        setError(err.response.data.message);
      } else {
        setError("Something went wrong. Try again later.");
      }
    }
  };

  return (
    <div className="login-container">
      <div className="login-box">
        <div className="login-logo">
          <img src="/images/logoO.png" alt="iRabiesCare Logo" className="logo" />
        </div>

        <form className="login-form" onSubmit={handleLogin}>
          {error && <p className="error-text">{error}</p>}

          <input
            type="email"
            name="email"
            placeholder="Email"
            value={formData.email}
            onChange={handleChange}
            className="login-input"
            required
          />
          <input
            type="password"
            name="password"
            placeholder="Password"
            value={formData.password}
            onChange={handleChange}
            className="login-input"
            required
          />

          <button type="submit" className="login-btn">Log In</button>

          <p className="register-text">
            Don’t have an account? <a href="/register">Register here</a>
          </p>
        </form>
      </div>
    </div>
  );
}
