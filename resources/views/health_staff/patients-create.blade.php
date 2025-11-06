@extends('health_staff.layout')

@section('title', 'Create Patient')

@section('content')
{{-- FontAwesome icons are loaded via layout --}}
<style>
body { background: #f5f6f7; font-family: "Inter", system-ui, sans-serif; }
.edit-card { max-width:1150px; margin:2.5rem auto; background:#fff; border-radius:14px; padding:2rem; border:1px solid #e5e7eb; box-shadow:0 8px 20px rgba(0,0,0,0.05); }
.edit-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem }
.edit-title { display:flex; align-items:center; gap:1rem }
.edit-icon { width:3rem; height:3rem; background:#000; color:#fff; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem 2rem }
.form-group { display:flex; flex-direction:column }
.form-group label { font-weight:600; color:#374151; margin-bottom:0.35rem; font-size:0.9rem; text-transform:uppercase }
.form-group input, .form-group textarea, .form-group select { padding:0.7rem 0.8rem; border:1px solid #d1d5db; border-radius:8px; font-size:0.95rem; background:#f9fafb }
.form-actions { display:flex; gap:1rem; justify-content:flex-end; margin-top:2rem }
.btn-primary { background:#000; color:#fff; padding:0.7rem 1.3rem; border-radius:8px; border:none }
.btn-secondary { background:#f3f4f6; color:#111; padding:0.7rem 1.3rem; border-radius:8px; border:none }
@media(max-width:800px){ .form-grid{grid-template-columns:1fr} .form-actions{flex-direction:column} }
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
        <a href="{{ route('health_staff.patients.index') }}" class="btn-secondary">
            <i class="bi bi-arrow-left-circle"></i>&nbsp; Back to Patients
        </a>
    </div>

    <form method="POST" action="{{ route('health_staff.patients.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group"><label>Name</label><input name="name" required/></div>
            <div class="form-group"><label>Email</label><input name="email" type="email"/></div>
            <div class="form-group"><label>Date of Birth</label><input name="dob" type="date"/></div>
            <div class="form-group"><label>Gender</label><input name="gender"/></div>
            <div class="form-group"><label>Contact</label><input name="contact"/></div>
            <div class="form-group"><label>Emergency Contact</label><input name="emergency_contact"/></div>
            <div class="form-group"><label>Address</label><textarea name="address"></textarea></div>
            <div class="form-group"><label>Clinic</label><input name="clinic"/></div>
            <div class="form-group"><label>Exposure Date</label><input name="exposure_date" type="date"/></div>
            <div class="form-group"><label>Exposure Type</label><input name="exposure_type"/></div>
            <div class="form-group"><label>Animal</label><input name="animal"/></div>
            <div class="form-group"><label>Vaccination Status</label>
                <select name="vaccination_status">
                    <option value="">Select</option>
                    <option value="Not Vaccinated">Not Vaccinated</option>
                    <option value="Partially Vaccinated">Partially Vaccinated</option>
                    <option value="Fully Vaccinated">Fully Vaccinated</option>
                </select>
            </div>
            <div class="form-group"><label>Last Dose</label><input name="last_dose" type="date"/></div>
            <div class="form-group"><label>Status</label>
                <select name="status">
                    <option value="Active">Active</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="form-group" style="grid-column:1/3;"><label>Notes</label><textarea name="notes"></textarea></div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary"><i class="bi bi-check-circle"></i>&nbsp; Create Patient</button>
            <a href="{{ route('health_staff.patients.index') }}" class="btn-secondary"><i class="bi bi-x-circle"></i>&nbsp; Cancel</a>
        </div>
    </form>
</div>
@endsection
