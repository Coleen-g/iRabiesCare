<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rabies Case Management & Vaccination Monitoring System</title>

  <!-- 🌿 Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Icons loaded locally from /images/icons/ to avoid CDN/CSP issues -->

  <style>
    :root { --nav-height: 80px; }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: #ffffff;
      overflow-x: hidden;
      padding-top: var(--nav-height);
    }

    /* ===========================
       🌿 NAVBAR
    ============================ */
    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 8%;
      position: fixed;
      top: 0;
      width: 100%;
      height: var(--nav-height);
      z-index: 100;
      backdrop-filter: blur(6px);
      background: linear-gradient(180deg, rgba(0,0,0,0.35), rgba(0,0,0,0.12));
    }

    .logo img {
      height: 80px;
      object-fit: contain;
    }

    nav ul {
      display: flex;
      list-style: none;
      gap: 30px;
      align-items: center;
    }

    nav ul li a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      letter-spacing: 0.5px;
      transition: color 0.3s ease, transform 0.3s ease;
      position: relative;
    }

    nav ul li a::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: -4px;
      width: 0%;
      height: 3px;
      background: linear-gradient(90deg, #00e4d0, #b2fab4);
      transition: width 0.3s ease;
      border-radius: 2px;
    }

    nav ul li a:hover,
    nav ul li a.active {
      color: #b2fab4;
      transform: translateY(-2px);
    }

    nav ul li a:hover::after,
    nav ul li a.active::after {
      width: 100%;
    }

    .nav-buttons {
      display: flex;
      gap: 10px;
    }

    .btn,
    .btn-outline {
      padding: 12px 26px;
      border-radius: 30px;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
    }

    .btn {
      background: linear-gradient(90deg, #43a047, #66bb6a);
      color: #fff;
      box-shadow: 0 4px 15px rgba(67,160,71,0.4);
    }

    .btn-outline {
      border: 2px solid #fff;
      color: #fff;
      background: transparent;
    }

    .btn:hover,
    .btn-outline:hover {
      transform: translateY(-3px) scale(1.05);
    }

    .btn-outline:hover {
      background: #fff;
      color: #43a047;
    }

    /* ===========================
       🌿 HERO SECTION
    ============================ */
    .hero {
      display: flex;
      align-items: center;
      justify-content: space-between;
      min-height: calc(100vh - var(--nav-height));
      background: linear-gradient(to right, #a8e063 0%, #56ab2f 35%, #ffffff 100%);
      position: relative;
      overflow: hidden;
    }

    .hero::after {
      content: "";
      position: absolute;
      right: 0;
      top: 0;
      width: 60%;
      height: 100%;
      background: url('/images/injection.webp') right center/cover no-repeat;
      mask-image: linear-gradient(to left, rgba(0,0,0,1) 65%, rgba(0,0,0,0));
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
      margin-bottom: 20px;
    }

    .hero-content h1 span {
      color: #e8f5e9;
    }

    .hero-content p {
      font-size: 18px;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    /* ===========================
       🌿 ABOUT SECTION
    ============================ */
    .about {
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      gap: 40px;
      padding: 60px 8%;
      background: #fff;
      min-height: calc(70vh - var(--nav-height));
      /* professional card look */
      --card-radius: 18px;
    }
    .about-image {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .about-image img {
      width: 100%;
      max-width: 460px;
      height: auto;
      border-radius: var(--card-radius);
      box-shadow: 0 20px 40px rgba(16, 24, 32, 0.12);
      object-fit: cover;
      transform: translateY(-4px);
      border: 1px solid rgba(6, 95, 70, 0.06);
    }

    .about-text {
      max-width: 680px;
      padding: 28px 18px;
      background: linear-gradient(180deg, #ffffff, #f7fff7);
      border-radius: var(--card-radius);
      box-shadow: 0 12px 30px rgba(6, 95, 70, 0.06);
      border: 1px solid rgba(6,95,70,0.04);
    }

    .about-text h2 {
      font-size: 34px;
      color: #0f4b23;
      margin-bottom: 12px;
    }

    .about-text p {
      font-size: 16px;
      color: #3b4b3a;
      margin-bottom: 18px;
      line-height: 1.7;
    }

    .about-features {
      list-style: none;
      padding: 0;
      margin: 0 0 20px 0;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px 18px;
    }

    .about-features li {
      position: relative;
      padding-left: 28px;
      color: #2f4a2f;
      font-weight: 500;
    }

    .about-features li::before {
      content: '✓';
      position: absolute;
      left: 0;
      top: 0;
      color: #2e7d32;
      background: rgba(46,125,50,0.08);
      width: 22px;
      height: 22px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      font-size: 12px;
      line-height: 1;
    }

    .about-cta {
      display: flex;
      gap: 12px;
      justify-content: flex-start;
      margin-top: 8px;
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
      line-height: 1.6;
    }

    /* ===========================
       🌿 CONTACT SECTION
    ============================ */
    #contact {
      background: linear-gradient(to bottom right, #e8f5e9, #a8e063);
      padding: 80px 8%;
      /* make contact occupy full screen space beneath nav when possible */
      min-height: calc(100vh - var(--nav-height));
      box-sizing: border-box;
    }

    /* make anchor navigation position content below fixed nav */
    [id] {
      scroll-margin-top: calc(var(--nav-height) + 8px);
    }

    #contact h2 {
      font-size: 42px;
      color: #1b5e20;
      margin-bottom: 10px;
    }

    #contact p {
      color: #2e7d32;
      font-size: 18px;
      margin-bottom: 40px;
    }

    .contact-container {
      display: flex;
      gap: 50px;
      flex-wrap: wrap;
      justify-content: center;
      max-width: 1100px;
      margin: 0 auto;
    }

    .contact-card {
      background: #fff;
      border-radius: 14px;
      padding: 24px;
      box-shadow: 0 6px 18px rgba(67,160,71,0.15);
      color: #374151;
      display: flex;
      gap: 14px;
      align-items: flex-start;
      min-height: 72px; /* ensure enough space for icon + text */
      width: 100%;
      max-width: 720px;
    }

    .contact-card .card-icon {
      flex: 0 0 40px;
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: linear-gradient(180deg, rgba(46,125,50,0.06), rgba(46,125,50,0.02));
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #2e7d32;
    }

    .contact-card .card-icon svg {
      width: 20px;
      height: 20px;
      display: block;
      fill: currentColor;
    }

    .contact-card .card-content {
      flex: 1 1 auto;
    }

    .contact-form {
      flex: 1;
      background: #ffffff;
      border-radius: 18px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(76,175,80,0.25);
      max-width: 540px;
    }

    /* wrapper for centering the contact cards when there is no form */
    .contact-cards {
      display: flex;
      flex-direction: column;
      gap: 20px;
      align-items: center;
      width: 100%;
      padding: 0 12px;
      box-sizing: border-box;
    }

    .contact-form input,
    .contact-form select,
    .contact-form textarea {
      width: 100%;
      padding: 14px;
      border-radius: 10px;
      border: 1px solid #c8e6c9;
      outline: none;
      font-size: 16px;
      margin-bottom: 14px;
    }

    /* ===========================
       🌿 FOOTER
    ============================ */
    .site-footer {
      /* Match the site's green color scheme */
      background: linear-gradient(135deg, #072a12 0%, #0f4b23 45%, #163d57 100%);
      color: #d7ead8;
      padding: 3.5rem 1rem 1rem;
    }

    .footer-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 2.5rem;
      padding-bottom: 2rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .footer-logo img {
      width: 140px;
      margin-bottom: 1rem;
    }

    .footer-desc {
      color: #9fb4c2;
      font-size: 0.95rem;
      margin-bottom: 1.2rem;
      line-height: 1.6;
    }

    .footer-column h4 {
      color: #ffffff;
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .footer-links {
      list-style: none;
      padding: 0;
    }

    .footer-links li {
      margin-bottom: 0.6rem;
    }

    .footer-links a {
      color: #cdd9e3;
      text-decoration: none;
      transition: color 0.3s, transform 0.2s;
    }

    .footer-links a:hover {
      color: #b2fab4;
      transform: translateX(4px);
    }

    .social-icons {
      display: flex;
      gap: 10px;
    }

    .social-icons a {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.03);
      border-radius: 50%;
      color: #b2fab4; /* light green to match site */
      font-size: 1.0rem;
      transition: background 0.3s, transform 0.3s, color 0.3s;
      text-decoration: none;
    }

    .social-icons a img {
      width: 18px;
      height: 18px;
      display: block;
      filter: none;
    }

    /* ensure inline SVG icons inherit the text color and size properly */
    .social-icons svg {
      width: 18px;
      height: 18px;
      display: block;
      fill: currentColor;
    }

    .social-icons a:hover {
      background: linear-gradient(90deg, #43a047, #66bb6a);
      color: #ffffff;
      transform: translateY(-3px);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 1.8rem;
      color: #9fb4c2;
      font-size: 0.9rem;
    }

    /* ===========================
       🌿 RESPONSIVE
    ============================ */
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

      .about {
        flex-direction: column;
        text-align: center;
      }
    }

    @media (max-width: 768px) {
      .footer-grid {
        grid-template-columns: 1fr 1fr;
      }
    }

    @media (max-width: 480px) {
      .footer-grid {
        grid-template-columns: 1fr;
        text-align: center;
      }
      .social-icons { justify-content: center; }
    }
  </style>
</head>

<body>
  <!-- ===========================
       🌿 NAVBAR
  ============================ -->
  <nav>
    <div class="logo">
      <img src="/images/logo.png" alt="Rabies Management Logo">
    </div>
    <ul>
      <li><a href="#hero" class="nav-link">Dashboard</a></li>
      <li><a href="#about" class="nav-link">Services</a></li>
      <li><a href="#contact" class="nav-link">Contact</a></li>
    </ul>
    <div class="nav-buttons">
      <a href="{{ route('login') }}" class="btn-outline">Login</a>
      <a href="{{ route('register') }}" class="btn">Register</a>
    </div>
  </nav>

  <!-- ===========================
       🌿 HERO
  ============================ -->
  <section class="hero" id="hero">
    <div class="hero-content">
      <h1>Best Outcome for <span>Every Patient!</span></h1>
      <p>Monitor rabies cases and vaccination efforts efficiently — empowering communities to prevent outbreaks.</p>
      <a href="{{ route('register') }}" class="btn">Get Started</a>
    </div>
  </section>

  <!-- ===========================
       🌿 ABOUT
  ============================ -->
  <section class="about" id="about">
    <div class="about-image">
      <img src="/images/vaccine.jpg" alt="Clinic Staff">
    </div>
    <div class="about-text">
      <h2>We Are the Best Clinic in the City</h2>
      <p>With over 15 years of trusted healthcare experience, our dedicated team of medical professionals provides comprehensive rabies prevention, vaccination, and post-exposure treatment. We combine compassion, innovation, and clinical expertise to ensure the highest standard of care.</p>

      <ul class="about-features">
        <li>Experienced & certified medical staff</li>
        <li>24/7 emergency support</li>
        <li>Comprehensive vaccination tracking</li>
        <li>Community outreach programs</li>
      </ul>

      <div class="about-cta">
      
        <a href="#" class="btn-outline">Learn More</a>
      </div>
    </div>
  </section>

  <!-- ===========================
       🌿 CONTACT
  ============================ -->
  <section id="contact">
    <div style="text-align:center;">
      <h2>Contact Us</h2>
      <p>We’d love to hear from you! Reach out for appointments, inquiries, or vaccination information.</p>
    </div>

    <div class="contact-container">
      <!-- Centered Info Cards -->
      <div class="contact-cards">
        <div class="contact-card">
          <div class="card-icon" aria-hidden="true">
            <!-- Location SVG -->
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 .001-5.001A2.5 2.5 0 0 1 12 11.5z"/>
            </svg>
          </div>
          <div class="card-content">
            <h4>Clinic Address</h4>
            <p>St.1 San Jose, Talibon, Bohol</p>
          </div>
        </div>

        <div class="contact-card">
          <div class="card-icon" aria-hidden="true">
            <!-- Phone SVG -->
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false">
              <path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.12.37 2.33.57 3.59.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.07 21 3 13.93 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.26.2 2.47.57 3.59a1 1 0 0 1-.25 1.01l-2.2 2.2z"/>
            </svg>
          </div>
          <div class="card-content">
            <h4>Contact Numbers</h4>
            <p>63-993-630-9575 - DITO<br>63-926-463-5671 - TM</p>
          </div>
        </div>

        <div class="contact-card">
          <div class="card-icon" aria-hidden="true">
            <!-- Mail SVG -->
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false">
              <path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
          </div>
          <div class="card-content">
            <h4>Email</h4>
            <p>iRabiesCare - kolsgonz@gmail.com</p>
          </div>
        </div>

        <div class="contact-card">
          <div class="card-icon" aria-hidden="true">
            <!-- Clock / Hours SVG -->
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false">
              <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 11h4a1 1 0 0 1 0 2h-5a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v6z"/>
            </svg>
          </div>
          <div class="card-content">
            <h4>Clinic Hours</h4>
            <p>Mon - Sat: 8:00 AM - 6:00 PM<br>Sun: Emergency Only</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===========================
       🌿 FOOTER
  ============================ -->
  <footer class="site-footer" role="contentinfo">
    <div class="footer-container">
      <div class="footer-grid">
        <div class="footer-logo">
          <img src="/images/logo.png" alt="Rabies Management Logo">
          <p class="footer-desc">Delivering reliable rabies case and vaccination monitoring since 2025.</p>

          <div class="social-icons" aria-hidden="false">
            <a href="#" aria-label="Facebook" title="Facebook">
              <!-- Facebook SVG -->
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                <path d="M22 12.07C22 6.48 17.52 2 11.93 2S2 6.48 2 12.07C2 17.09 5.66 21.18 10.44 21.98v-7.01H7.9v-2.9h2.54V9.41c0-2.5 1.49-3.88 3.77-3.88 1.09 0 2.23.2 2.23.2v2.45h-1.25c-1.23 0-1.61.77-1.61 1.56v1.87h2.74l-.44 2.9h-2.3V22C18.34 21.18 22 17.09 22 12.07z"/>
              </svg>
            </a>

            <a href="#" aria-label="Twitter" title="Twitter">
              <!-- Twitter SVG -->
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                <path d="M22 5.92c-.63.28-1.3.48-2.01.57a3.5 3.5 0 0 0 1.54-1.94 7.04 7.04 0 0 1-2.22.85 3.51 3.51 0 0 0-6 3.2A9.96 9.96 0 0 1 3.16 4.9a3.5 3.5 0 0 0 1.09 4.68c-.5 0-.97-.15-1.38-.38v.04c0 1.71 1.21 3.14 2.82 3.47a3.5 3.5 0 0 1-1.38.05c.39 1.22 1.52 2.11 2.86 2.14A7.03 7.03 0 0 1 2 19.54a9.94 9.94 0 0 0 5.39 1.58c6.47 0 10.01-5.36 10.01-10.01v-.46c.68-.48 1.28-1.09 1.75-1.79-.63.28-1.3.49-2.01.58z"/>
              </svg>
            </a>

            <a href="#" aria-label="LinkedIn" title="LinkedIn">
              <!-- LinkedIn SVG -->
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                <path d="M20.45 20.45h-3.55v-5.47c0-1.3-.03-2.97-1.8-2.97-1.8 0-2.07 1.4-2.07 2.87v5.57H8.4V9h3.41v1.56h.05c.48-.9 1.65-1.85 3.39-1.85 3.63 0 4.3 2.39 4.3 5.48v6.77zM5.34 7.43a2.07 2.07 0 1 1 0-4.14 2.07 2.07 0 0 1 0 4.14zM6.11 20.45H4.57V9h1.54v11.45z"/>
              </svg>
            </a>
          </div>
        </div>

        <div class="footer-column">
          <h4>About</h4>
          <ul class="footer-links">
            <li><a href="#">Our Story</a></li>
            <li><a href="#">The Team</a></li>
            <li><a href="#">Career</a></li>
            <li><a href="#">Contact Us</a></li>
          </ul>
        </div>

        <div class="footer-column">
          <h4>Product</h4>
          <ul class="footer-links">
            <li><a href="#">For Health Workers</a></li>
            <li><a href="#">For Admins</a></li>
            <li><a href="#">Features</a></li>
            <li><a href="#">Pricing</a></li>
          </ul>
        </div>

        <div class="footer-column">
          <h4>Resources</h4>
          <ul class="footer-links">
            <li><a href="#">Insights</a></li>
            <li><a href="#">Events</a></li>
            <li><a href="#">Guides</a></li>
            <li><a href="#">Press Release</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 Rabies Case Management System. All rights reserved.</p>
      </div>
    </div>
  </footer>
</body>
  <script>
    // Smooth-scroll nav links and ensure content sits below fixed nav.
    (function () {
      const navHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 80;
      const links = document.querySelectorAll('a.nav-link');
      links.forEach(link => {
        link.addEventListener('click', function (e) {
          const href = this.getAttribute('href');
          if (!href || !href.startsWith('#')) return;
          const target = document.querySelector(href);
          if (!target) return;
          e.preventDefault();
          // Use scrollIntoView; CSS scroll-margin-top keeps content visible under fixed nav.
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          // Update active class
          links.forEach(l => l.classList.remove('active'));
          this.classList.add('active');
        });
      });
    })();
  </script>
</html>
