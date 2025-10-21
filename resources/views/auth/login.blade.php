<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - iRabiesCare</title>

  <style>
    /* ===== General Reset ===== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: linear-gradient(135deg, #43a047, #66bb6a, #a8e6cf);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    /* ===== Wrapper ===== */
    .login-wrapper {
      width: 900px;
      height: 550px;
      display: flex;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* ===== Left Section ===== */
    .login-left {
      flex: 1;
      background: linear-gradient(135deg, #43a047, #66bb6a);
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 40px;
    }

    .login-left img {
      width: 180px;
      margin-bottom: 20px;
      filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.2));
    }

    .login-left h1 {
      font-size: 30px;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .login-left p {
      font-size: 15px;
      line-height: 1.5;
      max-width: 300px;
      opacity: 0.95;
    }

    /* ===== Right Section (Glassmorphism) ===== */
    .login-right {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(25px);
      -webkit-backdrop-filter: blur(25px);
      border-left: 1px solid rgba(255, 255, 255, 0.3);
    }

    .login-box {
      width: 80%;
      max-width: 340px;
      background: rgba(255, 255, 255, 0.45);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .login-box h2 {
      font-size: 26px;
      color: #2e2e2e;
      margin-bottom: 8px;
    }

    .login-sub {
      color: #4a4a4a;
      margin-bottom: 25px;
      font-size: 14px;
    }

    /* ===== Input Fields ===== */
    .input-group {
      margin-bottom: 15px;
    }

    .input-group input {
      width: 100%;
      padding: 12px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
      transition: 0.3s;
      background: rgba(255, 255, 255, 0.7);
    }

    .input-group input:focus {
      border-color: #43a047;
      outline: none;
      box-shadow: 0 0 8px rgba(67, 160, 71, 0.3);
    }

    /* ===== Remember + Forgot ===== */
    .remember-forgot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 13px;
      margin-bottom: 20px;
    }

    .remember-forgot label {
      color: #444;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .remember-forgot a {
      text-decoration: none;
      color: #43a047;
      font-weight: 500;
    }

    .remember-forgot a:hover {
      text-decoration: underline;
    }

    /* ===== Button ===== */
    .login-btn {
      width: 100%;
      background: linear-gradient(90deg, #43a047, #66bb6a);
      color: #fff;
      padding: 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
      box-shadow: 0 4px 10px rgba(67, 160, 71, 0.4);
    }

    .login-btn:hover {
      background: linear-gradient(90deg, #388e3c, #4caf50);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(67, 160, 71, 0.5);
    }

    /* ===== Register Text ===== */
    .register-text {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #444;
    }

    .register-text a {
      color: #43a047;
      font-weight: bold;
      text-decoration: none;
    }

    .register-text a:hover {
      text-decoration: underline;
    }

    /* ===== Error Message ===== */
    .error-text {
      color: #b91c1c;
      background: #fee2e2;
      padding: 8px;
      border-radius: 6px;
      margin-bottom: 12px;
      text-align: center;
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
      .login-wrapper {
        flex-direction: column;
        width: 90%;
        height: auto;
      }

      .login-left {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <!-- Left Section -->
    <div class="login-left">
      <img src="/images/logo.png" alt="System Logo">
      <h1>Welcome to iRabiesCare</h1>
      <p>Track, manage, and prevent rabies cases and vaccinations efficiently for a safer community.</p>
    </div>

    <!-- Right Section -->
    <div class="login-right">
      <div class="login-box">
        <h2>Login</h2>
        <p class="login-sub">Welcome back! Please log in to continue.</p>

        @if(session('error'))
          <p class="error-text">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
          </div>
          <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
          </div>

          <div class="remember-forgot">
            <label><input type="checkbox" name="remember"> Remember me</label>
            <a href="#">Forgot Password?</a>
          </div>

          <button type="submit" class="login-btn">LOGIN</button>

          <p class="register-text">New user? <a href="{{ route('register') }}">Sign up</a></p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
