import React, { useState } from "react";
import axios from "axios";
import "../../../css/RegisterPage.css";

function RegisterPage() {
  const [step, setStep] = useState(1);

  const [formData, setFormData] = useState({
    fullName: "",
    dob: "",
    gender: "",
    address: "",
    contact: "",
    email: "",
    exposureDate: "",
    exposureType: "",
    animal: "",
    vaccinationStatus: "",
    lastDoseDate: "",
    clinic: "",
    // credentials will be provided by admin
    username: "",
    password: "",
    confirmPassword: "",
    emergencyContact: "",
  });

  const [error, setError] = useState("");
    const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const nextStep = () => setStep((s) => Math.min(4, s + 1));
  const prevStep = () => setStep((s) => Math.max(1, s - 1));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);
    try {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const payload = {
        // patient fields (credentials are not created by the registrant)
        fullName: formData.fullName,
        dob: formData.dob,
        gender: formData.gender,
        address: formData.address,
        contact: formData.contact,
        exposureDate: formData.exposureDate,
        exposureType: formData.exposureType,
        animal: formData.animal,
        vaccinationStatus: formData.vaccinationStatus,
        lastDoseDate: formData.lastDoseDate,
        clinic: formData.clinic,
        emergencyContact: formData.emergencyContact,
        email: formData.email,
      };

      const res = await axios.post('/register', payload, { headers: { 'X-CSRF-TOKEN': token } });
      if (res.status === 201 || res.status === 200) {
        // If server returned a patient object (JSON) redirect to the completion
        // page which shows the patient id. Otherwise fall back to a safe redirect.
        const patient = res.data.patient;
        if (patient && patient.id) {
          window.location = `/register/complete/${patient.id}`;
          return;
        }
        // fallback
        window.location = '/login';
      }
    } catch (err) {
      const r = err.response || err;
      // reset field errors
      setErrors({});
      if (r.data?.errors) {
        // map server snake_case keys to camelCase form keys
        const mapKey = (k) => k.replace(/_([a-z])/g, (m, p1) => p1.toUpperCase());
        const fieldErrors = {};
        Object.entries(r.data.errors).forEach(([k, v]) => {
          fieldErrors[mapKey(k)] = Array.isArray(v) ? v.join(' ') : v;
        });
        setErrors(fieldErrors);
        setError(Object.values(fieldErrors).join(' '));
        // Move to the step that contains the first field with an error so the user sees it.
        const stepMap = {
          1: ['fullName', 'dob', 'gender', 'address', 'contact', 'email'],
          2: ['exposureDate', 'exposureType', 'animal', 'vaccinationStatus', 'lastDoseDate', 'clinic'],
          3: [], // account details are provided by admin
          4: ['emergencyContact'],
        };
        const firstKey = Object.keys(fieldErrors)[0];
        for (const [s, keys] of Object.entries(stepMap)) {
          if (keys.includes(firstKey)) {
            setStep(Number(s));
            break;
          }
        }
      } else if (r.data?.message) {
        setError(r.data.message);
      } else {
        setError('Registration failed.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="register-container">
      <form className="register-form" onSubmit={handleSubmit}>
        <div className="register-logo">
          <img src="/images/logoO.png" alt="iRabiesCare Logo" />
        </div>
          <h2>Patient Registration</h2>
          <p className="step-indicator">Step {step} of 4</p>
          {error && <p className="error-text">{error}</p>}

          {/* STEP 1 */}
          {step === 1 && (
            <div>
              <h3>Personal Information</h3>
              <input type="text" name="fullName" placeholder="Full Name" value={formData.fullName} onChange={handleChange} required />
              {errors.fullName && <p className="error-text">{errors.fullName}</p>}
              <input type="date" name="dob" value={formData.dob} onChange={handleChange} required />
              {errors.dob && <p className="error-text">{errors.dob}</p>}
              <select name="gender" value={formData.gender} onChange={handleChange} required>
                <option value="">Select Gender</option>
                <option>Male</option>
                <option>Female</option>
              </select>
              <input type="text" name="address" placeholder="Complete Address" value={formData.address} onChange={handleChange} required />
              <input type="text" name="contact" placeholder="Contact Number" value={formData.contact} onChange={handleChange} required />
              {errors.contact && <p className="error-text">{errors.contact}</p>}
              <input type="email" name="email" placeholder="Email (optional)" value={formData.email} onChange={handleChange} />
              {errors.email && <p className="error-text">{errors.email}</p>}
              <button type="button" onClick={nextStep}>Next →</button>
            </div>
          )}

          {/* STEP 2 */}
          {step === 2 && (
            <div>
              <h3>Medical Information</h3>
              <input type="date" name="exposureDate" value={formData.exposureDate} onChange={handleChange} />
              <select name="exposureType" value={formData.exposureType} onChange={handleChange}>
                <option value="">Type of Exposure</option>
                <option>Bite</option>
                <option>Scratch</option>
                <option>Lick on wound</option>
              </select>
              <input type="text" name="animal" placeholder="Animal Involved (Dog, Cat, etc.)" value={formData.animal} onChange={handleChange} />
              {errors.animal && <p className="error-text">{errors.animal}</p>}
              <select name="vaccinationStatus" value={formData.vaccinationStatus} onChange={handleChange}>
                <option value="">Vaccination Status</option>
                <option>First time (no doses yet)</option>
                <option>Ongoing (already received doses)</option>
                <option>Completed</option>
              </select>
              <input type="date" name="lastDoseDate" value={formData.lastDoseDate} onChange={handleChange} />
              <input type="text" name="clinic" placeholder="Clinic/Hospital Name" value={formData.clinic} onChange={handleChange} />
              {errors.clinic && <p className="error-text">{errors.clinic}</p>}

              <div className="buttons">
                <button type="button" onClick={prevStep}>← Back</button>
                <button type="button" onClick={nextStep}>Next →</button>
              </div>
            </div>
          )}

          {/* STEP 3 - Account Details (admin will provide credentials) */}
          {step === 3 && (
            <div>
              <h3>Account Details</h3>
              <p className="text-sm">You will be assigned a username and password by the clinic administrator. Please proceed to the next step.</p>
              <div className="buttons">
                <button type="button" onClick={prevStep}>← Back</button>
                <button type="button" onClick={nextStep}>Next →</button>
              </div>
            </div>
          )}

          {/* STEP 4 */}
          {step === 4 && (
            <div>
              <h3>Emergency Contact</h3>
              <input type="text" name="emergencyContact" placeholder="Emergency Contact (Name & Number)" value={formData.emergencyContact} onChange={handleChange} />
              {errors.emergencyContact && <p className="error-text">{errors.emergencyContact}</p>}

              <label className="consent">
                <input type="checkbox" required /> I consent to my data being used for vaccination monitoring.
              </label>

              <div className="buttons">
                <button type="button" onClick={prevStep}>← Back</button>
                <button type="submit" disabled={loading}>{loading ? 'Submitting...' : 'Submit ✔'}</button>
              </div>
            </div>
          )}
        </form>
    </div>
  );
}

export default RegisterPage;
