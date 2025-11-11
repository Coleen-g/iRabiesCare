@extends('health_staff.layout')

@section('title', 'Patient')

@section('content')
<style>
body{background:#f5f6f7;font-family:Inter,system-ui,sans-serif}
.patient-card{background:#fff;border-radius:14px;box-shadow:0 8px 20px rgba(0,0,0,0.05);border:1px solid #e5e7eb;padding:2rem;max-width:1150px;margin:2.5rem auto}
.patient-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem}
.patient-title{display:flex;align-items:center;gap:1rem}
.patient-icon{width:3rem;height:3rem;background:#000;border-radius:12px;color:#fff;display:flex;align-items:center;justify-content:center}
.patient-main{display:grid;grid-template-columns:2fr 1fr;gap:2rem}
.patient-info{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem 2rem}
.patient-info-item strong{color:#4b5563;font-size:0.9rem;font-weight:600;display:block;margin-bottom:0.25rem;text-transform:uppercase}
.patient-info-item div{color:#111827;font-size:0.95rem}
.patient-sidebar{background:#111;border-radius:12px;padding:1.5rem;color:#f9fafb;display:flex;flex-direction:column;gap:1.5rem}
.status-badge{display:inline-block;padding:0.45rem 1.2rem;border-radius:9999px;font-size:0.85rem;font-weight:600}
.btn-primary{display:inline-flex;align-items:center;justify-content:center;padding:10px 20px;background:#000;color:#fff;border-radius:8px}
</style>

<div class="patient-card">
    <div class="patient-header">
        <div class="patient-title">
            <div class="patient-icon"><i class="bi bi-person"></i></div>
            <div>
                <h2>{{ $patient->name }}</h2>
                <small style="color:#6b7280;font-size:0.95rem;">{{ $patient->type ?? '' }}</small>
            </div>
        </div>
        <div style="display:flex;gap:.5rem;align-items:center">
            <a href="#" onclick="history.back(); return false;" class="btn-primary" style="background:#fff;color:#111;border:1px solid #e5e7eb;"> 
                <i class="bi bi-arrow-left" style="margin-right:.5rem"></i> Back
            </a>
            <a href="{{ route('health_staff.patients.edit', $patient) }}" class="btn-primary">Edit</a>
        </div>
    </div>

    <div class="patient-main">
        <div class="patient-info">
            <div class="patient-info-item"><strong>Contact</strong><div>{{ $patient->contact ?? '-' }}</div></div>
            <div class="patient-info-item"><strong>Email</strong><div>{{ $patient->email ?? '-' }}</div></div>
            <div class="patient-info-item"><strong>Address</strong><div>{{ $patient->address ?? '-' }}</div></div>
            <div class="patient-info-item"><strong>Date of Birth</strong><div>{{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('M d, Y') : '-' }}</div></div>
            <div class="patient-info-item"><strong>Exposure Date</strong><div>{{ $patient->exposure_date ? \Carbon\Carbon::parse($patient->exposure_date)->format('M d, Y') : '-' }}</div></div>
            <div class="patient-info-item"><strong>Exposure Type</strong><div>{{ $patient->exposure_type ?? '-' }}</div></div>
            <div class="patient-info-item"><strong>Animal</strong><div>{{ $patient->animal ?? '-' }}</div></div>
            <div class="patient-info-item"><strong>Location of Wounds</strong><div>{{ $patient->wounds_location ?? '-' }}</div></div>
            <div class="patient-info-item"><strong>Animal Status</strong><div>{{ $patient->animal_status ?? '-' }}</div></div>
            <div class="patient-info-item"><strong>Vaccination Status</strong><div>{{ $patient->vaccination_status ?? '-' }}</div></div>
            <div class="patient-info-item"><strong>Last Dose</strong><div>{{ $patient->last_dose ? \Carbon\Carbon::parse($patient->last_dose)->format('M d, Y') : '-' }}</div></div>
            <div class="patient-info-item"><strong>Clinic</strong><div>{{ $patient->clinic ?? '-' }}</div></div>
            <div class="patient-info-item"><strong>Emergency Contact</strong><div>{{ $patient->emergency_contact ?? '-' }}</div></div>
            <div class="patient-info-item" style="grid-column:1/3;"><strong>Notes</strong><div>{{ $patient->notes ?? 'No notes' }}</div></div>
        </div>

        <div class="patient-sidebar">
            <div>
                <strong>Status</strong>
                <div style="margin-top:.5rem"><span class="status-badge {{ strtolower($patient->status ?? 'active') }}">{{ $patient->status ?? 'Active' }}</span></div>
            </div>
            <div>
                <strong>Registered</strong>
                <div style="margin-top:.5rem">{{ $patient->created_at->format('M d, Y') }}</div>
            </div>
            <div>
                <strong>Assigned Health Staff</strong>
                <div style="margin-top:.5rem">{{ optional($patient->assignedHealthStaff->first())->name ?? 'Unassigned' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
