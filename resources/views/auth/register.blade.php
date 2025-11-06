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
        <input type="text" name="fullName" placeholder="Full Name (e.g. Juan Dela Cruz)" required>
  <input type="date" name="dob" placeholder="Date of Birth" required>
        <select name="gender" required>
          <option value="">Select Gender</option>
          <option>Male</option>
          <option>Female</option>
          <option>Other</option>
        </select>
        <!-- Municipality (Bohol) + Barangay (dynamic) -->
        <label for="municipality" style="display:none">Municipality</label>
        <select name="municipality" id="municipality" required>
          <option value="">Select Municipality (Bohol)</option>
          <option>Alburquerque</option>
          <option>Alicia</option>
          <option>Anda</option>
          <option>Antequera</option>
          <option>Baclayon</option>
          <option>Balilihan</option>
          <option>Batuan</option>
          <option>Bien Unido</option>
          <option>Buenavista</option>
          <option>Calape</option>
          <option>Candijay</option>
          <option>Carmen</option>
          <option>Catigbian</option>
          <option>Clarin</option>
          <option>Corella</option>
          <option>Cortes</option>
          <option>Dagohoy</option>
          <option>Danao</option>
          <option>Dauis</option>
          <option>Dimiao</option>
          <option>Duero</option>
          <option>Garcia Hernandez</option>
          <option>Guindulman</option>
          <option>Inabanga</option>
          <option>Jagna</option>
          <option>Lila</option>
          <option>Loay</option>
          <option>Loboc</option>
          <option>Mabini</option>
          <option>Maribojoc</option>
          <option>Panglao</option>
          <option>Pilar</option>
          <option>Pres. Carlos P. Garcia</option>
          <option>Sagbayan</option>
          <option>Sikatuna</option>
          <option>Sierra Bullones</option>
          <option>Tagbilaran City</option>
          <option>Talibon</option>
          <option>Talibon</option>
          <option>Trinidad</option>
          <option>Tubigon</option>
          <option>Ubay</option>
        </select>

  <label for="barangay">Barangay</label>
  <select name="barangay" id="barangay"></select>
  <input type="text" name="barangay_other" id="barangay_other" placeholder="Barangay (type if not listed)" style="display:none" />
  <!-- Hidden address field concatenated from municipality + barangay for DB storage -->
  <input type="hidden" name="address" id="address_hidden" value="" />
        <input type="text" name="contact" placeholder="Contact Number (mobile or landline)" required>
        <input type="email" name="email" placeholder="Email (required)" required>
        <div class="buttons">
          <span></span>
          <button type="button" onclick="nextStep()">Next →</button>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="form-step">
        <h3>Medical Information</h3>
  <input type="date" name="exposureDate" placeholder="Exposure Date">
        <select name="exposureType">
          <option value="">Type of Exposure</option>
          <option>Bite</option>
          <option>Scratch</option>
          <option>Lick on wound</option>
          <option>Other</option>
        </select>
        <input type="text" name="animal" placeholder="Animal Involved (e.g. Dog, Cat)">
        <select name="vaccinationStatus">
          <option value="">Vaccination Status</option>
          <option>First time (no doses yet)</option>
          <option>Ongoing (already received doses)</option>
          <option>Completed</option>
        </select>
  <input type="date" name="lastDoseDate" placeholder="Last Dose Date">
        <input type="text" name="clinic" placeholder="Clinic/Hospital Name (if applicable)">
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
        <input type="text" name="emergencyContact" placeholder="Emergency Contact (Name & Number, e.g. Maria - 09171234567)" required>
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

    function isStepValid(index) {
      // find all form controls inside the given step that have the required attribute
      const inputs = steps[index].querySelectorAll('[required]');
      for (let input of inputs) {
        // treat checkbox required differently
        if (input.type === 'checkbox') {
          if (!input.checked) return false;
          continue;
        }

        if (!input.value || !input.value.toString().trim()) {
          return false;
        }

        // extra check for email fields
        if (input.type === 'email') {
          const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(".+"))@(([^<>()[\]\\.,;:\s@\"]+\.)+[^<>()[\]\\.,;:\s@\"]{2,})$/i;
          if (!re.test(input.value)) return false;
        }
      }

      return true;
    }

    function nextStep() {
      // validate current step before advancing
      if (!isStepValid(currentStep)) {
        alert('Please complete all required fields in this step before continuing.');
        return;
      }

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
      // Validate final step required inputs too
      if (!isStepValid(currentStep)) {
        alert('Please complete all required fields in this step before submitting.');
        return;
      }
      if (!emergency || !emergency.value.trim()) {
        alert('Please provide an emergency contact before submitting.');
        return;
      }
      if (!consent || !consent.checked) {
        alert('Please provide consent to continue.');
        return;
      }
      // Ensure address hidden field is up-to-date before submitting
      updateHiddenAddress();
      // Submit the form programmatically (avoids issues with multi-step buttons)
      document.getElementById('registerForm').submit();
    }

    // -- Date placeholder helper: show 'YYYY-MM-DD' when empty and switch to native picker on focus
    function setupDatePlaceholders() {
      const placeholders = {
        dob: 'Date of Birth',
        exposureDate: 'Exposure Date',
        lastDoseDate: 'Last Dose Date'
      };

      Object.keys(placeholders).forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (!input) return;

        const placeholder = placeholders[name];

        // Initialize: if empty, show as text with placeholder so users see the format
        try {
          if (!input.value) {
            input.type = 'text';
            input.placeholder = placeholder;
            input.classList.add('date-placeholder');
          }
        } catch (e) {
          // some browsers may prevent changing type; in that case we still set placeholder attribute
          input.setAttribute('placeholder', placeholder);
        }

        input.addEventListener('focus', () => {
          try {
            input.type = 'date';
          } catch (e) {}
          // remove visual placeholder when using native picker
          if (input.placeholder) input.removeAttribute('placeholder');
          // try to open native picker where supported
          if (typeof input.showPicker === 'function') {
            try { input.showPicker(); } catch (e) {}
          }
        });

        input.addEventListener('blur', () => {
          if (!input.value) {
            try {
              input.type = 'text';
              input.placeholder = placeholder;
            } catch (e) {
              input.setAttribute('placeholder', placeholder);
            }
          }
        });
      });
    }

    // initialize date placeholders and show first step
    document.addEventListener('DOMContentLoaded', () => {
      setupDatePlaceholders();
      showStep(0);
      setupMunicipalityBarangays();
    });

    // municipality -> barangays mapping loader
    function setupMunicipalityBarangays() {
      const inlineMapping = {
        'Tagbilaran City': ['Bool', 'Cagbuan', 'Cogon', 'Danao', 'Daorong', 'Guingona', 'Kimsan', 'Manaba', 'Panglao', 'Tiptip'],
        'Panglao': ['Doljo', 'Danao', 'Bohol', 'Pob.'],
        'Carmen': ['Cantagay', 'Jaduan', 'Candabong', 'Mabini', 'Poblacion'],
        'Anda': ['Buenavista', 'Cabuntog', 'Canhepoy', 'Poblacion'],
        'Dauis': ['Agape', 'Bacani', 'Bogo', 'Buenavista', 'Dauis Poblacion']
      };

      const municipality = document.getElementById('municipality');
      const barangaySel = document.getElementById('barangay');
      const barangayOther = document.getElementById('barangay_other');

      function clearBarangays() {
        barangaySel.innerHTML = '';
      }

      // Populate the barangay select with a list and add an 'Other (type)' option
      function showSelect(list) {
        clearBarangays();
        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = 'Select Barangay';
        barangaySel.appendChild(defaultOpt);
        list.forEach(b => {
          const o = document.createElement('option');
          o.value = b;
          o.textContent = b;
          barangaySel.appendChild(o);
        });
        const other = document.createElement('option');
        other.value = '__other__';
        other.textContent = 'Other (type)';
        barangaySel.appendChild(other);
        barangaySel.style.display = '';
        barangaySel.disabled = false;
        barangaySel.required = true;
        barangayOther.style.display = 'none';
        barangayOther.required = false;
      }

      // When municipality has no known barangays, present 'Other (type)' as the only option
      function showOther() {
        clearBarangays();
        const choose = document.createElement('option');
        choose.value = '';
        choose.textContent = 'Choose or type barangay below';
        barangaySel.appendChild(choose);
        const other = document.createElement('option');
        other.value = '__other__';
        other.textContent = 'Other (type)';
        barangaySel.appendChild(other);
        barangaySel.style.display = '';
        barangaySel.disabled = false;
        barangaySel.required = false;
        barangayOther.style.display = '';
        barangayOther.required = true;
      }

      function attachMapping(mapping) {
        municipality.addEventListener('change', () => {
          const val = municipality.value;
          if (!val) {
            clearBarangays();
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Select municipality first';
            barangaySel.appendChild(placeholder);
            barangaySel.disabled = true;
            barangaySel.required = false;
            barangayOther.style.display = 'none';
            barangayOther.required = false;
            updateHiddenAddress();
            return;
          }

          if (mapping[val] && mapping[val].length) {
            showSelect(mapping[val]);
          } else {
            // if we don't have a mapping for this municipality, present Other option and show manual input
            showOther();
          }
          updateHiddenAddress();
        });

        // initial state: disable barangay until municipality is chosen
        clearBarangays();
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Select municipality first';
        barangaySel.appendChild(placeholder);
        barangaySel.disabled = true;
        barangayOther.style.display = 'none';
        barangayOther.required = false;
      }

      // Try to load local JSON mapping first
      fetch('/data/bohol_barangays.json').then(r => {
        if (!r.ok) throw new Error('no local json');
        return r.json();
      }).then(json => {
        attachMapping(json);
        // whenever mapping is attached, also wire address updates so the hidden address follows the selected values
        wireAddressUpdater();
      }).catch(() => {
        // fallback to inline mapping
        attachMapping(inlineMapping);
        wireAddressUpdater();
      });
    }

    // Keep the hidden `address` input updated as "Municipality, Barangay"
    function updateHiddenAddress() {
      const muni = document.getElementById('municipality');
      const barangaySel = document.getElementById('barangay');
      const barangayOther = document.getElementById('barangay_other');
      const hidden = document.getElementById('address_hidden');

      const muniVal = muni && muni.value ? muni.value.trim() : '';
      let barangayVal = '';
      if (barangaySel && barangaySel.style.display !== 'none' && barangaySel.value) barangayVal = barangaySel.value.trim();
      else if (barangayOther && barangayOther.style.display !== 'none' && barangayOther.value) barangayVal = barangayOther.value.trim();

      let addr = '';
      if (muniVal && barangayVal) addr = `${muniVal}, ${barangayVal}`;
      else if (muniVal) addr = muniVal;
      else if (barangayVal) addr = barangayVal;

      if (hidden) hidden.value = addr;
    }

    // Wire change listeners so hidden address updates live when municipality/barangay change
    function wireAddressUpdater() {
      const muni = document.getElementById('municipality');
      const barangaySel = document.getElementById('barangay');
      const barangayOther = document.getElementById('barangay_other');

      if (muni) muni.addEventListener('change', updateHiddenAddress);
      if (barangaySel) barangaySel.addEventListener('change', updateHiddenAddress);
      if (barangayOther) barangayOther.addEventListener('input', updateHiddenAddress);
      // update now to reflect initial values
      updateHiddenAddress();
    }
  </script>
</body>
</html>
