<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rabies Case Management & Vaccination Monitoring System</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: #ffffff;
      overflow-x: hidden;
    }

    /* Navbar */
 /* Navbar */
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 8%;
  position: absolute;
  width: 100%;
  top: 0;
  z-index: 100;
  height: 80px; /* ✅ Fixed height so links don't move */
}

/* ✅ Logo image stays contained without resizing nav */
.logo {
  display: flex;
  align-items: center;
  height: 100%;
}

.logo img {
  margin-top: 20px;
  height: 100px; /* Adjust freely — it won’t move other items */
  width: auto;
  object-fit: contain;
}

/* Navigation links */
/* Navigation links */
nav ul {
  display: flex;
  list-style: none;
  gap: 30px;
  align-items: center;
}

nav ul li a {
  position: relative;
  text-decoration: none;
  color: #fff;
  font-weight: 500;
  letter-spacing: 0.5px;
  transition: color 0.3s ease, transform 0.3s ease;
  padding: 5px 0;
}

/* ✅ Smooth underline animation */
nav ul li a::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  width: 0%;
  height: 5px;
  background: linear-gradient(90deg, #00e4d0, #b2fab4);
  transition: width 0.3s ease;
  border-radius: 2px;
}

/* Hover effects */
nav ul li a:hover {
  color: #b2fab4;
  transform: translateY(-2px); /* slight lift */
}

nav ul li a:hover::after {
  width: 100%;
}

/* Active link (optional) */
nav ul li a.active {
  color: #b2fab4;
}

nav ul li a.active::after {
  width: 100%;
}

/* Buttons stay aligned */
.nav-buttons {
  display: flex;
  gap: 10px;
  align-items: center;
}

.btn,
.btn-outline {
  padding: 12px 26px;
  border-radius: 30px;
  text-decoration: none;
  font-weight: 600;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
  position: relative;
  cursor: pointer;
  overflow: hidden;
  display: inline-block;
}

/* Filled button */
.btn {
  background: linear-gradient(90deg, #43a047, #66bb6a);
  color: #fff;
  box-shadow: 0 4px 15px rgba(67, 160, 71, 0.4);
}

/* Outline button */
.btn-outline {
  border: 2px solid #fff;
  color: #fff;
  background: transparent;
}

/* Subtle ripple hover effect */
.btn::before,
.btn-outline::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0%;
  height: 0%;
  background: rgba(255, 255, 255, 0.25);
  border-radius: 50%;
  transform: translate(-50%, -50%);
  transition: width 0.4s ease, height 0.4s ease, opacity 0.4s ease;
  opacity: 0;
}

.btn:hover::before,
.btn-outline:hover::before {
  width: 300%;
  height: 300%;
  opacity: 1;
}

/* Hover interactions */
.btn:hover {
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 8px 25px rgba(67, 160, 71, 0.5);
}

.btn-outline:hover {
  background: #fff;
  color: #43a047;
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
}

/* Active press feedback */
.btn:active,
.btn-outline:active {
  transform: scale(0.97);
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
}


    /* HERO SECTION */
    .hero {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: space-between;
      min-height: 100vh;
      background: linear-gradient(to right, #a8e063 0%, #56ab2f 35%, #ffffff 100%);
      overflow: hidden;
    }

    /* Smooth fade of doctor image */
    .hero::after {
      content: "";
      position: absolute;
      right: 0;
      top: 0;
      width: 60%;
      height: 100%;
      background: url('/images/dog.jpg') right center/cover no-repeat;
      mask-image: linear-gradient(to left, rgba(0,0,0,1) 65%, rgba(0,0,0,0) 100%);
      -webkit-mask-image: linear-gradient(to left, rgba(0,0,0,1) 65%, rgba(0,0,0,0) 100%);
      opacity: 0.95;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      padding: 0 8%;
      max-width: 550px;
      color: #fff;
    }

    .hero-content h1 {
      font-size: 52px;
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 20px;
      color: #fff;
    }

    .hero-content h1 span {
      color: #e8f5e9;
    }

    .hero-content p {
      font-size: 18px;
      margin-bottom: 30px;
      line-height: 1.6;
      color: #f1f1f1;
    }

    .hero-buttons a {
      display: inline-block;
      margin-right: 10px;
    }

    /* About Section */
    .about {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 100px 8%;
      gap: 50px;
      background: #fff;
    }

    .about img {
      width: 340px;
      border-radius: 50%;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .about-text h2 {
      font-size: 36px;
      font-weight: 700;
      color: #1b5e20;
      margin-bottom: 20px;
    }

    .about-text p {
      font-size: 18px;
      color: #555;
      margin-bottom: 30px;
    }

    footer {
      background: #1b5e20;
      color: #fff;
      text-align: center;
      padding: 40px 0;
    }

    @media (max-width: 900px) {
      .hero {
        flex-direction: column;
        background: linear-gradient(to bottom, #81c784, #a5d6a7);
      }

      .hero::after {
        position: relative;
        width: 100%;
        height: 300px;
        mask-image: none;
      }

      .hero-content {
        text-align: center;
        padding: 80px 5%;
      }

      .about {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <nav>
    <div class="logo">
      <img src="/images/logo.png" alt="Rabies Management Logo">
    </div>
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="#">Services</a></li>
      <li><a href="#">Reports</a></li>
      <li><a href="#">Contact</a></li>
    </ul>
    <div class="nav-buttons">
      <a href="{{ route('login') }}" class="btn-outline">Login</a>
      <a href="{{ route('register') }}" class="btn">Register</a>
    </div>
  </nav>

  <section class="hero">
    <div class="hero-content">
      <h1>Best Outcome for <span>Every Patient!</span></h1>
      <p>Monitor rabies cases and vaccination efforts efficiently — empowering communities to prevent outbreaks.</p>
      <div class="hero-buttons">
        <a href="{{ route('register') }}" class="btn">Get Started</a>
      </div>
    </div>
  </section>

  <section class="about">
    <img src="/images/nurse.jpg" alt="Clinic Staff">
    <div class="about-text">
      <h2>We Are the Best Clinic in the City</h2>
      <p>We have over 15 years of experience in providing high-quality rabies prevention and treatment services to our patients. Your safety is our mission.</p>
      <a href="#" class="btn">Learn More</a>
    </div>
  </section>

  <footer>
    &copy; 2025 Rabies Case Management System. All rights reserved.
  </footer>

</body>
</html>
