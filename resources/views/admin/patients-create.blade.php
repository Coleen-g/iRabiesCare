@extends('admin.layout')

@section('title','Create Patient')

@section('content')
<style>
body {
    background: #f5f6f7;
    font-family: "Inter", system-ui, sans-serif;
}
.edit-card {
    max-width: 1150px;
    margin: 2.5rem auto;
    background: #fff;
    border-radius: 14px;
    padding: 2rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    transition: 0.3s ease;
}
.edit-card:hover {
    box-shadow: 0 12px 24px rgba(0,0,0,0.08);
}
.edit-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}
.edit-title {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.edit-icon {
    width: 3rem;
    height: 3rem;
    background: #000;
    color: #fff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    transition: background 0.3s ease, transform 0.2s ease;
}
.edit-icon:hover {
    background: #111;
    transform: scale(1.1);
}
.edit-title h2 {
    margin: 0;
    font-size: 1.6rem;
    font-weight: 700;
    color: #111827;
}
form .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem 2rem;
}
.form-group {
    display: flex;
    flex-direction: column;
}
.form-group label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.35rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.form-group input,
.form-group textarea,
.form-group select {
    padding: 0.7rem 0.8rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.95rem;
    background: #f9fafb;
    color: #111827;
    transition: border 0.3s ease, box-shadow 0.3s ease;
}
.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    border-color: #000;
    box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
    outline: none;
    background: #fff;
}
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}
.btn-primary,
.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    transition: 0.3s ease;
}
.btn-primary {
    background: #000;
    color: #fff;
    padding: 0.7rem 1.3rem;
}
.btn-primary:hover {
    background: #222;
    transform: translateY(-2px);
}
.btn-secondary {
    background: #f3f4f6;
    color: #111;
    padding: 0.7rem 1.3rem;
}
.btn-secondary:hover {
    background: #e5e7eb;
    transform: translateY(-2px);
}
@media(max-width:800px) {
    form .form-grid { grid-template-columns: 1fr; }
    .form-actions { flex-direction: column; align-items: stretch; }
}
</style>

<div class="edit-card">
    <div class="edit-header">
        <div class="edit-title">
            <div class="edit-icon"><i class="bi bi-person-plus"></i></div>
            <div>
                <h2>Create Patient</h2>
                <small style="color:#6b7280">Enter new patient details below</small>
            </div>
        </div>
        <a href="{{ route('admin.patients.index') }}" class="btn-secondary">
            <i class="bi bi-arrow-left-circle"></i>&nbsp; Back to Patients
        </a>
    </div>

    <form method="POST" action="{{ route('admin.patients.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label>Name</label>
                <input name="name" required />
            </div>

            <div class="form-group">
                <label>Email</label>
                <input name="email" type="email" />
            </div>

            <div class="form-group">
                <label>Date of Birth</label>
                <input name="dob" type="date" />
            </div>

            <div class="form-group">
                <label>Gender</label>
                <select name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Contact</label>
                <input 
                    name="contact"
                    maxlength="15"
                    pattern="[0-9]{1,15}"
                    title="Numbers only, maximum 15 digits"
                    required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                />
            </div>

            <div class="form-group">
                <label>Emergency Contact</label>
                <input 
                    name="emergency_contact"
                    maxlength="15"
                    pattern="[0-9]{1,15}"
                    title="Numbers only, maximum 15 digits"
                    required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                />
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address"></textarea>
            </div>

            <div class="form-group">
                <label>Clinic</label>
                <input name="clinic" />
            </div>

            <div class="form-group">
                <label>Exposure Date</label>
                <input name="exposure_date" type="date" />
            </div>

            <div class="form-group">
                <label>Exposure Type</label>
                <input name="exposure_type" />
            </div>

            <div class="form-group">
                <label>Animal</label>
                <input name="animal" />
            </div>

            <div class="form-group">
                <label>Animal Status</label>
                <select name="animal_status">
                    <option value="">Select</option>
                    <option value="Alive">Alive</option>
                    <option value="Dead">Dead</option>
                    <option value="Unknown">Unknown</option>
                </select>
            </div>

            <div class="form-group">
                <label>Location of Wounds</label>
                <input name="wounds_location" />
            </div>

            <div class="form-group">
                <label>Vaccination Status</label>
                <select name="vaccination_status">
                    <option value="">Select</option>
                    <option value="Not Vaccinated">Not Vaccinated</option>
                    <option value="Partially Vaccinated">Partially Vaccinated</option>
                    <option value="Fully Vaccinated">Fully Vaccinated</option>
                </select>
            </div>

            <div class="form-group">
                <label>Last Dose</label>
                <input name="last_dose" type="date" />
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="Active">Active</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <div class="form-group" style="grid-column:1/3;">
                <label>Notes</label>
                <textarea name="notes"></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="bi bi-check-circle"></i>&nbsp; Create Patient
            </button>
            <a href="{{ route('admin.patients.index') }}" class="btn-secondary">
                <i class="bi bi-x-circle"></i>&nbsp; Cancel
            </a>
        </div>
    </form>
</div>
@endsection
