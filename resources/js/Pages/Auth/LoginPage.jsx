import React, { useState } from 'react';
import axios from 'axios';
import '../../../css/LoginPage.css';

export default function LoginPage() {
  const [formData, setFormData] = useState({ username: '', password: '' });
  const [error, setError] = useState('');

  const handleChange = (e) => setFormData({ ...formData, [e.target.name]: e.target.value });

  const handleLogin = async (e) => {
    e.preventDefault();
    setError('');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    try {
      // Use username authentication (username is required client-side)
      const payload = { username: formData.username, password: formData.password };
      const res = await axios.post('/login', payload, { headers: { 'X-CSRF-TOKEN': token } });
      const user = res.data.user;
      if (user && user.role === 'admin') {
        window.location = '/admin/dashboard';
      } else {
        window.location = '/user/dashboard';
      }
    } catch (err) {
      const r = err.response || err;
      if (r.status === 419) {
        setError('Session expired or CSRF mismatch. Please refresh and try again.');
      } else if (r.data?.message) {
        setError(r.data.message);
      } else {
        setError('Something went wrong. Try again later.');
      }
    }
  };

  return (
    <div className="login-container">
      <div className="login-box">
        <div className="login-logo">
          <img src="/images/logoO.png" alt="iRabiesCare Logo" className="login-logo" />
        </div>

        <form className="login-form" onSubmit={handleLogin}>
          {error && <p className="error-text">{error}</p>}

          <input type="text" name="username" placeholder="Username" value={formData.username} onChange={handleChange} className="login-input" required />
          <input type="password" name="password" placeholder="Password" value={formData.password} onChange={handleChange} className="login-input" required />

          <button type="submit" className="login-btn">Log In</button>

          <p className="register-text">Don’t have an account? <a href="/register">Register here</a></p>
        </form>
      </div>
    </div>
  );
}
