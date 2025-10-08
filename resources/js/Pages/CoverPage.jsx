// resources/js/Pages/CoverPage.jsx
import { useState } from "react";
import LoginPage from "./Auth/LoginPage";
import "../../css/CoverPage.css";

export default function CoverPage() {
  const [showLogin, setShowLogin] = useState(false);

  return (
    <div className="cover-container">
      {!showLogin ? (
        <>
          {/* Navbar */}
          <div className="navbar">
            <div className="nav-left">
              <img src="/images/logoO.png" alt="iRabiesCare Logo" className="logo" />
            </div>

            <div className="nav-center">
              <nav>
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
              </nav>
            </div>

            <div className="nav-right">
              <button className="login-btn" onClick={() => setShowLogin(true)}>
                Log in
              </button>
              <a href="/register">
                <button className="register-btn">Register</button>
              </a>
            </div>
          </div>

          {/* Hero Section */}
          <div className="hero">
            <div className="hero-left">
              <h1>
                For families. For lives. <br />
                For a future <span>free from rabies.</span>
              </h1>
              <p>
                Manage your rabies vaccinations with confidence and real-time
                tracking. Protection without the hassle. More care where it matters
                most — your health.
              </p>
            </div>
           
          </div>
        </>
      ) : (
        <LoginPage />
      )}
    </div>
  );
}
