import { useState } from "react";
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
    username: "",
    password: "",
    confirmPassword: "",
    emergencyContact: ""
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const nextStep = () => setStep(step + 1);
  const prevStep = () => setStep(step - 1);

  const handleSubmit = (e) => {
    e.preventDefault();
    alert("Registration Submitted! (Backend integration later)");
    console.log(formData);
  };

  
  return (
    <div className="register-container">
      <form className="register-form" onSubmit={handleSubmit}>
        <h2>Patient Registration</h2>
        <p className="step-indicator">Step {step} of 4</p>

        {/* STEP 1 - Personal Info */}
        {step === 1 && (
          <div>
            <h3>Personal Information</h3>
            <input
              type="text"
              name="fullName"
              placeholder="Full Name"
              value={formData.fullName}
              onChange={handleChange}
              required
            />
            <input
              type="date"
              name="dob"
              value={formData.dob}
              onChange={handleChange}
              required
            />
            <select
              name="gender"
              value={formData.gender}
              onChange={handleChange}
              required
            >
              <option value="">Select Gender</option>
              <option>Male</option>
              <option>Female</option>
            </select>
            <input
              type="text"
              name="address"
              placeholder="Complete Address"
              value={formData.address}
              onChange={handleChange}
              required
            />
            <input
              type="text"
              name="contact"
              placeholder="Contact Number"
              value={formData.contact}
              onChange={handleChange}
              required
            />
            <input
              type="email"
              name="email"
              placeholder="Email (optional)"
              value={formData.email}
              onChange={handleChange}
            />
            <button type="button" onClick={nextStep}>
              Next →
            </button>
          </div>
        )}

        {/* STEP 2 - Medical Info */}
        {step === 2 && (
          <div>
            <h3>Medical Information</h3>
            <input
              type="date"
              name="exposureDate"
              value={formData.exposureDate}
              onChange={handleChange}
            />
            <select
              name="exposureType"
              value={formData.exposureType}
              onChange={handleChange}
            >
              <option value="">Type of Exposure</option>
              <option>Bite</option>
              <option>Scratch</option>
              <option>Lick on wound</option>
            </select>
            <input
              type="text"
              name="animal"
              placeholder="Animal Involved (Dog, Cat, etc.)"
              value={formData.animal}
              onChange={handleChange}
            />
            <select
              name="vaccinationStatus"
              value={formData.vaccinationStatus}
              onChange={handleChange}
            >
              <option value="">Vaccination Status</option>
              <option>First time (no doses yet)</option>
              <option>Ongoing (already received doses)</option>
              <option>Completed</option>
            </select>
            <input
              type="date"
              name="lastDoseDate"
              value={formData.lastDoseDate}
              onChange={handleChange}
            />
            <input
              type="text"
              name="clinic"
              placeholder="Clinic/Hospital Name"
              value={formData.clinic}
              onChange={handleChange}
            />

            <div className="buttons">
              <button type="button" onClick={prevStep}>
                ← Back
              </button>
              <button type="button" onClick={nextStep}>
                Next →
              </button>
            </div>
          </div>
        )}

        {/* STEP 3 - Account Info */}
        {step === 3 && (
          <div>
            <h3>Account Details</h3>
            <input
              type="text"
              name="username"
              placeholder="Username"
              value={formData.username}
              onChange={handleChange}
              required
            />
            <input
              type="password"
              name="password"
              placeholder="Password"
              value={formData.password}
              onChange={handleChange}
              required
            />
            <input
              type="password"
              name="confirmPassword"
              placeholder="Confirm Password"
              value={formData.confirmPassword}
              onChange={handleChange}
              required
            />

            <div className="buttons">
              <button type="button" onClick={prevStep}>
                ← Back
              </button>
              <button type="button" onClick={nextStep}>
                Next →
              </button>
            </div>
          </div>
        )}

        {/* STEP 4 - Emergency Contact & Consent */}
        {step === 4 && (
          <div>
            <h3>Emergency Contact</h3>
            <input
              type="text"
              name="emergencyContact"
              placeholder="Emergency Contact (Name & Number)"
              value={formData.emergencyContact}
              onChange={handleChange}
            />

            <label className="consent">
              <input type="checkbox" required /> I consent to my data being used
              for vaccination monitoring.
            </label>

            <div className="buttons">
              <button type="button" onClick={prevStep}>
                ← Back
              </button>
              <button type="submit">Submit ✔</button>
            </div>
          </div>
        )}
      </form>
    </div>
  );
}

export default RegisterPage;
