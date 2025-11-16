<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Registration | iRabiesCare</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    /* Match login color scheme and improve layout */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #43a047, #66bb6a, #a8e6cf);
      color: #fff;
      overflow: auto; /* allow scrolling on small screens to avoid overlap */
    }

    .register-container {
      background: rgba(255,255,255,0.18);
      backdrop-filter: blur(18px);
      padding: 28px 28px 20px;
      border-radius: 18px;
      width: 100%;
      max-width: 820px;
      border: 1px solid rgba(255,255,255,0.2);
      box-shadow: 0 12px 40px rgba(0,0,0,0.35);
      position: relative;
      max-height: calc(100vh - 80px); /* keep container within viewport */
      overflow-y: auto; /* allow internal scroll when content is tall */
    }

    .register-logo img {
      width: 140px;
      display: block;
      margin: 0 auto 10px;
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

    /* form steps use a responsive grid so inputs align nicely */
    .form-step {
      display: none;
      animation: fadeIn 0.4s ease;
      padding-top: 6px;
      box-sizing: border-box;
      min-height: 0; /* allow grid children to shrink without overflow */
    }

    .form-step.active {
      display: grid;
      grid-template-columns: 1fr;
      gap: 12px;
      align-items: start;
      align-content: start; /* ensure content starts at top */
      row-gap: 12px;
    }
    @media (min-width: 760px) {
      .form-step.active.two-col {
        grid-template-columns: 1fr 1fr;
      }
      .form-step .full-span { grid-column: 1 / -1; }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    input, select {
      width: 100%;
      display: block;
      padding: 12px 14px;
      margin: 0; /* spacing handled by grid gap */
      border: 1px solid rgba(0,0,0,0.06);
      border-radius: 8px;
      background: rgba(255,255,255,0.98);
      color: #082032;
      font-size: 14px;
      box-sizing: border-box;
    }

    input:focus, select:focus {
      outline: none;
      box-shadow: 0 0 0 6px rgba(67,160,71,0.12);
      border-color: rgba(67,160,71,0.9);
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
      margin-top: 12px;
      gap: 8px;
      align-items: center;
    }

    .btn-primary {
      background: linear-gradient(90deg, #43a047, #66bb6a);
      border: none;
      padding: 10px 18px;
      color: #fff;
      border-radius: 8px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 6px 18px rgba(67,160,71,0.18);
    }

    .btn-primary:hover { transform: translateY(-2px); }

    .btn-secondary {
      background: transparent;
      color: rgba(255,255,255,0.95);
      border: 1px solid rgba(255,255,255,0.18);
      padding: 10px 14px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
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
  <div id="existsError" class="error-text" style="display:none"></div>

      <!-- Step 1 -->
      <div class="form-step active two-col">
        <h3 class="full-span">Personal Information</h3>
        <input class="full-span" type="text" name="fullName" placeholder="Full Name (e.g. Juan Dela Cruz)" required>
        <input type="date" name="dob" placeholder="Date of Birth" required>
        <select name="gender" required>
          <option value="">Select Sex</option>
          <option>Male</option>
          <option>Female</option>
         
        </select>
        <!-- Municipality (Bohol) + Barangay (dynamic) -->
  <label for="municipality" style="display:none" class="full-span">Municipality</label>
  <select class="full-span" name="municipality" id="municipality" required>
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
          <option>Trinidad</option>
          <option>Tubigon</option>
          <option>Ubay</option>
        </select>

        <label for="barangay" class="full-span">Barangay</label>
        <select class="full-span" name="barangay" id="barangay"></select>
        <input class="full-span" type="text" name="barangay_other" id="barangay_other" placeholder="Barangay (type if not listed)" style="display:none" />
  <!-- Note: hidden combined address field removed. Municipality + Barangay are submitted separately. -->
  <input id="contactInput" type="tel" name="contact" placeholder="Contact Number (mobile or landline)" required maxlength="11" inputmode="numeric" pattern="\d+" title="Digits only, max 15 characters">
        <input type="email" name="email" placeholder="Email (required)" required>
        <div class="buttons full-span">
          <span></span>
          <button type="button" class="btn-primary" onclick="nextStep()">Next →</button>
        </div>
      </div>

      <!-- Step 2 -->
  <div class="form-step two-col">
    <h3 class="full-span">Medical Information</h3>
  <input type="date" name="exposureDate" placeholder="Exposure Date">
        <select name="exposureType">
          <option value="">Type of Exposure</option>
          <option>Bite</option>
          <option>Scratch</option>
          <option>Lick on wound</option>
          <option>Other</option>
        </select>
        <input type="text" name="animal" placeholder="Animal Involved (e.g. Dog, Cat)">
        <input type="text" name="woundsLocation" placeholder="Wounds location (e.g. left forearm, face)">
        <select name="vaccinationStatus">
          <option value="">Vaccination Status</option>
          <option>First time (no doses yet)</option>
          <option>Ongoing (already received doses)</option>
          <option>Completed</option>
        </select>
  <input type="date" name="lastDoseDate" placeholder="Last Dose Date">
  <input type="text" name="clinic" placeholder="Clinic/Hospital Name (if applicable)" value="Talibon-Branch">
        <div class="buttons full-span">
          <button type="button" class="btn-secondary" onclick="prevStep()">← Back</button>
          <button type="button" class="btn-primary" onclick="nextStep()">Next →</button>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="form-step two-col">
        <h3 class="full-span">Account Details</h3>
        <p style="font-size: 14px; color: #ddd;">Your login credentials will be provided by the clinic administrator.</p>
        <div class="buttons full-span">
          <button type="button" class="btn-secondary" onclick="prevStep()">← Back</button>
          <button type="button" class="btn-primary" onclick="nextStep()">Next →</button>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="form-step two-col">
        <h3 class="full-span">Emergency Contact</h3>
  <input id="emergencyInput" class="full-span" type="tel" name="emergencyContact" placeholder="Emergency Contact Number (e.g. 09171234567)" inputmode="numeric" pattern="\d*" maxlength="11" required>
        <div class="full-span">
          <label class="consent" style="justify-content:flex-start;">
            <input type="checkbox" required style="margin-right:8px;"> I consent to my data being used for vaccination monitoring.
          </label>
        </div>
        <div class="buttons full-span" style="margin-top:8px;">
          <button type="button" class="btn-secondary" onclick="prevStep()">← Back</button>
          <button type="button" class="btn-primary" onclick="submitRegistration()">Submit ✔</button>
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

        // extra check for contact number: digits only, max 15
        if (input.name === 'contact' || input.name === 'emergencyContact') {
          const val = input.value.replace(/\D+/g, '');
          const reNum = /^\d{1,15}$/;
          if (!reNum.test(val)) return false;
        }
      }

      return true;
    }

    async function nextStep() {
      // validate current step before advancing
      if (!isStepValid(currentStep)) {
        showExistsError('Please complete all required fields in this step before continuing.');
        return;
      }

      // On first step, verify the patient doesn't already exist (by name or email)
      if (currentStep === 0) {
        const fullName = (document.querySelector('input[name="fullName"]') || {}).value || '';
        const email = (document.querySelector('input[name="email"]') || {}).value || '';
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
        try {
          const res = await fetch('/register/check-exists', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            },
            body: JSON.stringify({ fullName: fullName, email: email })
          });

          if (res.status === 409) {
            const body = await res.json();
            showExistsError(body.message || 'A patient with this name or email already exists.');
            return;
          }
          if (!res.ok) {
            // network/server error
            showExistsError('Unable to validate uniqueness right now. Please try again.');
            return;
          }
          // success: clear any previous messages
          clearExistsError();
        } catch (err) {
          showExistsError('Unable to validate uniqueness right now. Please try again.');
          return;
        }
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
      // sanitize contact input: allow digits only and enforce maxlength
      const contact = document.getElementById('contactInput');
      if (contact) {
        contact.addEventListener('input', (e) => {
          // remove non-digits
          let cleaned = e.target.value.replace(/\D+/g, '');
          // enforce maxlength
          if (cleaned.length > 15) cleaned = cleaned.slice(0, 15);
          if (cleaned !== e.target.value) e.target.value = cleaned;
        });
      }
        // sanitize emergency contact: digits only and maxlength
        const emergency = document.getElementById('emergencyInput');
        if (emergency) {
          emergency.addEventListener('input', (e) => {
            let cleaned = e.target.value.replace(/\D+/g, '');
            if (cleaned.length > 15) cleaned = cleaned.slice(0, 15);
            if (cleaned !== e.target.value) e.target.value = cleaned;
          });
        }
    });

    function showExistsError(msg) {
      const el = document.getElementById('existsError');
      if (!el) return alert(msg);
      el.textContent = msg;
      el.style.display = 'block';
    }

    function clearExistsError() {
      const el = document.getElementById('existsError');
      if (!el) return;
      el.textContent = '';
      el.style.display = 'none';
    }

    // municipality -> barangays mapping loader
    function setupMunicipalityBarangays() {
      const inlineMapping = {
        "Alburquerque": ["Poblacion","Bugsoc","Danlugan","Lo-ok","Lugsong","San Isidro","Tugas"],
        "Alicia": ["Poblacion","Bunga Mar","Sapang Dalaga","Santo Niño","San Roque","Cabangtuan"],
        "Anda": ["Buenavista","Cabuntog","Can-iat","Can-oc","Canhepoy","Poblacion"],
        "Antequera": ["Poblacion","Basak","Buenavista","Maitum","San Pedro"],
        "Baclayon": ["Poblacion","Canhawit","Dangay","Looc","Poblacion West"],
        "Balilihan": ["Poblacion","Bangkal","Cabacungan","Cansague","Tugas"],
        "Batuan": ["Poblacion","Candayoc","Candili","Lo-ong","Santa Cruz"],
        "Bien Unido": ["Poblacion","Bunga","Luyang","Napo","Talisay"],
        "Buenavista": ["Poblacion","Guadalupe","San Roque","Tugas","Campo"],
        "Calape": ["Poblacion","Cebulok","Cambaquiz","Calunasan","Punta"],
        "Candijay": ["Poblacion","Bunga","Cagbong","Punta","San Miguel"],
        "Carmen": ["Poblacion","Cantagay","Jadjao","Mabini","Punta"],
        "Catigbian": ["Poblacion","Abihilan","San Isidro","Cubay","Tag-ilid"],
        "Clarin": ["Poblacion","East Poblacion","West Poblacion","Cabug","San Vicente"],
        "Corella": ["Poblacion","Cabuan","Linkon","Santo Niño"],
        "Cortes": ["Poblacion","Canha-ayon","Palanas","San Roque"],
        "Dagohoy": ["Poblacion","Kilahon","Cansuhay","San Isidro"],
        "Danao": ["Poblacion","San Roque","Samboan","Tugas"],
        "Dauis": ["Poblacion","Agape","Bacani","Buenavista","Dauis Poblacion"],
        "Dimiao": ["Poblacion","Garcia","San Vicente","Tabalong"],
        "Duero": ["Poblacion","San Miguel","Poblacion West","Tagbilaran"],
        "Garcia Hernandez": ["Poblacion","Poblacion Norte","San Jose","Tag-oro"],
        "Guindulman": ["Poblacion","Luna","San Roque","Tabuan"],
        "Inabanga": ["Poblacion","Jandayan","San Isidro","Villa-Angeles"],
        "Jagna": ["Poblacion","Alejawan","Balili","Boctol","Can-upao"],
        "Lila": ["Poblacion","San Jose","Doljo","Mabini"],
        "Loay": ["Poblacion","Tabuc","San Vicente","Can-uba"],
        "Loboc": ["Poblacion","Bahian","Tabalong","Pangapasan"],
        "Mabini": ["Poblacion","Maribojoc","San Roque","Punta"],
        "Maribojoc": ["Poblacion","Busalian","Cansayang","San Miguel"],
        "Panglao": ["Doljo","Danao","Bohol","Poblacion","Punta"],
        "Pilar": ["Poblacion","Can-ayan","San Roque","Tagbilaran"],
        "Pres. Carlos P. Garcia": ["Poblacion","Tagbilaran","Punta","San Roque"],
        "Sagbayan": ["Poblacion","San Isidro","Canlaon","Campaclan"],
        "Sikatuna": ["Poblacion","Can-avid","San Miguel","Tugas"],
        "Sierra Bullones": ["Poblacion","Candas","Magsaysay","San Roque"],
        "Tagbilaran City": ["Bool","Cagbuan","Cogon","Danao","Daorong","Guingona","Kimsan","Manaba","Panglao","Tiptip","Poblacion"],
        "Talibon": ["Poblacion","Bagacay","Balintawak","Burgos","Busalian","San Roque"],
        "Trinidad": ["Poblacion","Loay","Cabawan","San Miguel"],
        "Tubigon": ["Poblacion","Bagongbanwa","Banlasan","Bunacan","Cabulihan","Tinangnan"],
        "Ubay": ["Poblacion","Achila","Bay-ang","Buenavista","San Pascual","San Isidro"]
      };

      const municipality = document.getElementById('municipality');
      const barangaySel = document.getElementById('barangay');
      const barangayOther = document.getElementById('barangay_other');

      // Centralized listener: toggle the manual barangay input when the barangay select changes.
      // Having one listener prevents duplicate handlers when the select is rebuilt.
      barangaySel.addEventListener('change', function () {
        if (this.value === '__other__') {
          barangayOther.style.display = '';
          barangayOther.required = true;
          barangaySel.required = false;
        } else {
          barangayOther.style.display = 'none';
          barangayOther.required = false;
          barangaySel.required = true;
        }
      });

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
        // Manual input visibility is handled by the centralized change listener.
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
        // Manual input visibility is handled by the centralized change listener.
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
      }).catch(() => {
        // fallback to inline mapping
        attachMapping(inlineMapping);
      });
    }

    // Hidden combined address logic removed. Municipality and barangay are submitted as separate fields.
  </script>
</body>
</html>
