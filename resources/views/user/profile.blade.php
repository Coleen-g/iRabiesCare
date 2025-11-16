@extends('user.layout')

@section('title', 'My Profile')

@section('content')
    <style>
        body {
            background: linear-gradient(120deg, #e8f5e9 0%, #f1f8e9 100%);
        }
        .profile-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 1rem;
        }
        .profile-card {
            background: linear-gradient(120deg, #e8f5e9 0%, #a8e063 100%);
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(67,160,71,0.10);
            padding: 2.2rem 2.5rem;
            border: 1px solid #c8e6c9;
        }
        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #c8e6c9;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        .profile-header h2 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.6rem;
            font-weight: 700;
            color: #388e3c;
            margin: 0;
        }
        .profile-section {
            margin-bottom: 2rem;
        }
        .profile-section h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #388e3c;
            margin-bottom: 1rem;
            border-left: 4px solid #43a047;
            padding-left: 0.6rem;
            background: linear-gradient(90deg, #e8f5e9 0%, #a8e063 100%);
            border-radius: 6px;
        }
        .profile-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.25rem 2rem;
        }
        .profile-item {
            background: #fff;
            border-radius: 10px;
            padding: 1rem;
            border: 1px solid #c8e6c9;
            box-shadow: 0 2px 8px rgba(67,160,71,0.07);
        }
        .profile-item .label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #388e3c;
            margin-bottom: 0.3rem;
        }
        .profile-item div {
            font-size: 1rem;
            color: #222;
        }
        .muted {
            color: #9ca3af;
            font-style: italic;
            margin-left: 0.5rem;
        }
        .profile-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 2rem;
        }
        .btn-primary {
            background: linear-gradient(90deg, #388e3c 0%, #43a047 100%);
            color: #fff;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s ease, transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 2px 8px rgba(67,160,71,0.10);
        }
        .btn-primary:hover {
            background: #388e3c;
            color: #fff;
            transform: translateY(-1px) scale(1.04);
        }
        @media (max-width: 700px) {
            .profile-card {
                padding: 1.5rem;
            }
        }
    </style>

    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <h2><i class="bi bi-person-circle" style="color:#43a047;"></i>{{ ($user->patient)->name }}</h2>
            </div>

            {{-- Basic Information --}}
            <div class="profile-section">
                <h3>Account Information</h3>
                <div class="profile-details">
                    <div class="profile-item">
                        <div class="label">Email</div>
                        <div>{{ $user->email }}</div>
                    </div>

                    <div class="profile-item">
                        <div class="label">Role</div>
                        <div style="text-transform: capitalize">{{ $user->role }}</div>
                    </div>
                </div>
            </div>

            {{-- Patient Information --}}
            @php $patient = optional($user)->patient; @endphp
            <div class="profile-section">
                <h3>Patient Information</h3>
                @if($patient)
                    <div class="profile-details">
                        <div class="profile-item"><div class="label">Patient ID</div><div>{{ $patient->id_number ?? $patient->id }}</div></div>
                        <div class="profile-item"><div class="label">Phone</div><div>{{ $patient->contact ?? '—' }}</div></div>
                        <div class="profile-item"><div class="label">DOB</div><div>{{ $patient->dob ? \Illuminate\Support\Carbon::parse($patient->dob)->format('Y-m-d') : '—' }}</div></div>
                        <div class="profile-item"><div class="label">Gender</div><div>{{ $patient->gender ?? '—' }}</div></div>
                        <div class="profile-item"><div class="label">Address</div><div>{{ $patient->address ?? '—' }}</div></div>
                        <div class="profile-item"><div class="label">Clinic</div><div>{{ $patient->clinic ?? '—' }}</div></div>
                        <div class="profile-item"><div class="label">Vaccination Status</div><div>{{ $patient->vaccination_status ?? '—' }}</div></div>
                        <div class="profile-item"><div class="label">Last Dose Date</div><div>{{ $patient->last_dose_date ? \Illuminate\Support\Carbon::parse($patient->last_dose_date)->format('Y-m-d') : '—' }}</div></div>
                        <div class="profile-item"><div class="label">Exposure Date</div><div>{{ $patient->exposure_date ? \Illuminate\Support\Carbon::parse($patient->exposure_date)->format('Y-m-d') : '—' }}</div></div>
                        <div class="profile-item"><div class="label">Exposure Type</div><div>{{ $patient->exposure_type ?? '—' }}</div></div>
                        <div class="profile-item"><div class="label">Animal</div><div>{{ $patient->animal ?? '—' }}</div></div>
                        <div class="profile-item"><div class="label">Emergency Contact</div><div>{{ $patient->emergency_contact ?? '—' }}</div></div>
                    </div>
                @else
                    <p class="muted">No patient record linked to this account.</p>
                @endif
            </div>

            <div class="profile-actions">
                <a class="btn-primary" href="{{ \Illuminate\Support\Facades\Route::has('user.profile.edit') ? route('user.profile.edit') : '#' }}">
                    <i class="bi bi-pencil-square"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
@endsection
