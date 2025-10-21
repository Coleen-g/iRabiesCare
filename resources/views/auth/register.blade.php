<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Registration | iRabiesCare</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    /* Global background aligned to login page */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      color: #fff;
      overflow: hidden;
    }

    .register-container {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(15px);
      padding: 40px;
      border-radius: 20px;
      width: 100%;
      max-width: 500px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 4px 30px rgba(0,0,0,0.4);
      position: relative;
    }

    .register-logo img {
      width: 140px;
      display: block;
      margin: 0 auto 15px;
    }

    h2 {
      text-align: center;
      margin-bottom: 5px;
      font-size: 24px;
    }

    .step-indicator {
      text-align: center;
      color: #dcdcdc;
      font-size: 14px;
      margin-bottom: 20px;
    }

    .form-step {
      display: none;
      animation: fadeIn 0.5s ease;
    }

    .form-step.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    input, select {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 12px;
      border: none;
      border-radius: 8px;
      background: rgba(255,255,255,0.9);
      color: #000;
      font-size: 14px;
    }

    input::placeholder {
      color: #555;
    }

    select {
      cursor: pointer;
    }

    .buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
    }

    button {
      background: linear-gradient(90deg, #43a047, #66bb6a);
      border: none;
      padding: 10px 18px;
      color: #fff;
      border-radius: 25px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      transform: scale(1.07);
      background: linear-gradient(90deg, #2e7d32, #4caf50);
    }

    .consent {
      display: flex;
      align-items: center;
      font-size: 14px;
      color: #f0f0f0;
      margin: 10px 0;
    }

    .consent input {
      margin-right: 8px;
    }

    .error-text {
      background: #fee2e2;
      color: #b91c1c;
      padding: 8px;
      border-radius: 6px;
      margin-bottom: 8px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="register-container">
    <div class="register-logo">
      <img src="{{ asset('images/logo.png') }}" alt="iRabiesCare Logo">
    </div>

  <form method="POST" action="{{ url('/register') }}" id="registerForm">
      @csrf
      <h2>Patient Registration</h2>
      <p class="step-indicator" id="stepIndicator">Step 1 of 4</p>

      <!-- Step 1 -->
      <div class="form-step active">
        <h3>Personal Information</h3>
        <input type="text" name="fullName" placeholder="Full Name" required>
        <input type="date" name="dob" required>
        <select name="gender" required>
          <option value="">Select Gender</option>
          <option>Male</option>
          <option>Female</option>
        </select>
        <input type="text" name="address" placeholder="Complete Address" required>
        <input type="text" name="contact" placeholder="Contact Number" required>
        <input type="email" name="email" placeholder="Email (optional)">
        <div class="buttons">
          <span></span>
          <button type="button" onclick="nextStep()">Next →</button>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="form-step">
        <h3>Medical Information</h3>
        <input type="date" name="exposureDate">
        <select name="exposureType">
          <option value="">Type of Exposure</option>
          <option>Bite</option>
          <option>Scratch</option>
          <option>Lick on wound</option>
        </select>
        <input type="text" name="animal" placeholder="Animal Involved (Dog, Cat, etc.)">
        <select name="vaccinationStatus">
          <option value="">Vaccination Status</option>
          <option>First time (no doses yet)</option>
          <option>Ongoing (already received doses)</option>
          <option>Completed</option>
        </select>
        <input type="date" name="lastDoseDate">
        <input type="text" name="clinic" placeholder="Clinic/Hospital Name">
        <div class="buttons">
          <button type="button" onclick="prevStep()">← Back</button>
          <button type="button" onclick="nextStep()">Next →</button>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="form-step">
        <h3>Account Details</h3>
        <p style="font-size: 14px; color: #ddd;">Your login credentials will be provided by the clinic administrator.</p>
        <div class="buttons">
          <button type="button" onclick="prevStep()">← Back</button>
          <button type="button" onclick="nextStep()">Next →</button>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="form-step">
        <h3>Emergency Contact</h3>
        <input type="text" name="emergencyContact" placeholder="Emergency Contact (Name & Number)" required>
        <label class="consent">
          <input type="checkbox" required> I consent to my data being used for vaccination monitoring.
        </label>
        <div class="buttons">
          <button type="button" onclick="prevStep()">← Back</button>
          <button type="button" onclick="submitRegistration()">Submit ✔</button>
        </div>
      </div>
    </form>
  </div>

  <script>
    let currentStep = 0;
    const steps = document.querySelectorAll('.form-step');
    const stepIndicator = document.getElementById('stepIndicator');

    function showStep(index) {
      steps.forEach((step, i) => {
        step.classList.toggle('active', i === index);
      });
      stepIndicator.textContent = `Step ${index + 1} of ${steps.length}`;
    }

    function nextStep() {
      if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
      }
    }

    function prevStep() {
      if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
      }
    }

    function submitRegistration() {
      // Basic front-end checks for required final-step fields
      const emergency = document.querySelector('input[name="emergencyContact"]');
      const consent = document.querySelector('.consent input[type="checkbox"]');
      if (!emergency || !emergency.value.trim()) {
        alert('Please provide an emergency contact before submitting.');
        return;
      }
      if (!consent || !consent.checked) {
        alert('Please provide consent to continue.');
        return;
      }
      // Submit the form programmatically (avoids issues with multi-step buttons)
      document.getElementById('registerForm').submit();
    }
  </script>
</body>
</html>
