@extends('admin.layout')

@section('title', 'Edit Patient')

@section('content')
<style>
/* ========== PROFESSIONAL DASHBOARD STYLE (MATCHES PATIENT VIEW) ========== */
body {
    background: #f5f6f7;
    font-family: "Inter", system-ui, sans-serif;
}

/* --- Card --- */
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

/* --- Header --- */
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
h2 {
    margin: 0;
    font-size: 1.6rem;
    font-weight: 700;
    color: #111827;
}

/* --- Form --- */
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

/* --- Actions --- */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

/* --- Buttons --- */
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

/* --- Responsive --- */
@media(max-width:800px) {
    form .form-grid { grid-template-columns: 1fr; }
    .form-actions { flex-direction: column; align-items: stretch; }
}
</style>

<div class="edit-card">
    <div class="edit-header">
        <div class="edit-title">
            <div class="edit-icon"><i class="bi bi-person-gear"></i></div>
            <div>
                <h2>Edit Patient</h2>
                <small style="color:#6b7280">Modify patient details below</small>
            </div>
        </div>
        <a href="{{ route('admin.patients.show', $patient) }}" class="btn-secondary">
            <i class="bi bi-arrow-left-circle"></i>&nbsp; Back to Patient
        </a>
    </div>

    <form method="POST" action="{{ route('admin.patients.update', $patient) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label>Name</label>
                <input name="name" value="{{ old('name', $patient->name) }}" required />
            </div>

            <div class="form-group">
                <label>Email</label>
                <input name="email" type="email" value="{{ old('email', $patient->email) }}" />
            </div>

            <div class="form-group">
                <label>Date of Birth</label>
                <input name="dob" type="date" value="{{ old('dob', $patient->dob) }}" />
            </div>

            <div class="form-group">
                <label>Gender</label>
                <input name="gender" value="{{ old('gender', $patient->gender) }}" />
            </div>

            <div class="form-group">
                <label>Contact</label>
                <input name="contact" value="{{ old('contact', $patient->contact) }}" />
            </div>

            <div class="form-group">
                <label>Emergency Contact</label>
                <input name="emergency_contact" value="{{ old('emergency_contact', $patient->emergency_contact) }}" />
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address">{{ old('address', $patient->address) }}</textarea>
            </div>

            <div class="form-group">
                <label>Clinic</label>
                <input name="clinic" value="{{ old('clinic', $patient->clinic) }}" />
            </div>

            <div class="form-group">
                <label>Exposure Date</label>
                <input name="exposure_date" type="date" value="{{ old('exposure_date', $patient->exposure_date) }}" />
            </div>

            <div class="form-group">
                <label>Exposure Type</label>
                <input name="exposure_type" value="{{ old('exposure_type', $patient->exposure_type) }}" />
            </div>

            <div class="form-group">
                <label>Animal</label>
                <input name="animal" value="{{ old('animal', $patient->animal) }}" />
            </div>

            <div class="form-group">
                <label>Vaccination Status</label>
                <select name="vaccination_status">
                    <option value=""{{ old('vaccination_status', $patient->vaccination_status) == '' ? ' selected' : '' }}>Select</option>
                    <option value="Not Vaccinated"{{ old('vaccination_status', $patient->vaccination_status) == 'Not Vaccinated' ? ' selected' : '' }}>Not Vaccinated</option>
                    <option value="Partially Vaccinated"{{ old('vaccination_status', $patient->vaccination_status) == 'Partially Vaccinated' ? ' selected' : '' }}>Partially Vaccinated</option>
                    <option value="Fully Vaccinated"{{ old('vaccination_status', $patient->vaccination_status) == 'Fully Vaccinated' ? ' selected' : '' }}>Fully Vaccinated</option>
                </select>
            </div>

            <div class="form-group">
                <label>Last Dose</label>
                <input name="last_dose" type="date" value="{{ old('last_dose', $patient->last_dose) }}" />
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="Active"{{ old('status', $patient->status) == 'Active' ? ' selected' : '' }}>Active</option>
                    <option value="Pending"{{ old('status', $patient->status) == 'Pending' ? ' selected' : '' }}>Pending</option>
                    <option value="Completed"{{ old('status', $patient->status) == 'Completed' ? ' selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="form-group" style="grid-column:1/3;">
                <label>Notes</label>
                <textarea name="notes">{{ old('notes', $patient->notes) }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="bi bi-check-circle"></i>&nbsp; Update Patient
            </button>
            <a href="{{ route('admin.patients.show', $patient) }}" class="btn-secondary">
                <i class="bi bi-x-circle"></i>&nbsp; Cancel
            </a>
        </div>
    </form>
</div>
@endsection
