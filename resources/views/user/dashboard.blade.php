@extends('user.layout')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-grid">

        <!-- Left Column: Patient Profile & Info -->
        <div>
            <div class="card profile-card shadow-sm">
                <div class="profile-header">
                    <img src="/images/logoO.png" alt="logo" class="profile-logo" />
                    <div>
                        <div class="text-muted small">Welcome back,</div>
                        <div class="profile-name">{{ optional(optional(auth()->user())->patient)->name ?? auth()->user()->name }}</div>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="profile-section">
                    <h4 class="section-title">Patient Information</h4>

                    <div class="info-grid">
                        <div>
                            <p class="info-item"><span>ID:</span> {{ $patient->id_number ?? ($patient->id ?? '—') }}</p>
                            <p class="info-item"><span>Name:</span> {{ $patient->name ?? '—' }}</p>
                            <p class="info-item"><span>Phone:</span> {{ $patient->contact ?? '—' }}</p>
                            <p class="info-item"><span>Email:</span> {{ $patient->email ?? '—' }}</p>
                        </div>

                        <div>
                            <p class="info-item"><span>DOB:</span> {{ $patient->dob ? \Illuminate\Support\Carbon::parse($patient->dob)->format('Y-m-d') : '—' }}</p>
                            <p class="info-item"><span>Gender:</span> {{ $patient->gender ?? '—' }}</p>
                            <p class="info-item"><span>Linked Account:</span> {{ $patient->user_id ? 'Yes' : 'No' }}</p>
                            <p class="info-item"><span>Clinic:</span> {{ $patient->clinic ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div>
                            <h5 class="sub-title">Address</h5>
                            <p class="text-muted">{{ $patient->address ?? '—' }}</p>
                        </div>
                        <div>
                            <h5 class="sub-title">Emergency Contact</h5>
                            <p class="text-muted">{{ $patient->emergency_contact ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div>
                            <h5 class="sub-title">Exposure Details</h5>
                            <p class="text-muted">Date: {{ $patient->exposure_date ? \Illuminate\Support\Carbon::parse($patient->exposure_date)->format('Y-m-d') : '—' }}</p>
                            <p class="text-muted">Type: {{ $patient->exposure_type ?? '—' }}</p>
                            <p class="text-muted">Animal: {{ $patient->animal ?? '—' }}</p>
                        </div>

                        <div>
                            <h5 class="sub-title">Vaccination Details</h5>
                            <p class="text-muted">Status: {{ $patient->vaccination_status ?? '—' }}</p>
                            <p class="text-muted">Last Dose: {{ $patient->last_dose_date ? \Illuminate\Support\Carbon::parse($patient->last_dose_date)->format('Y-m-d') : '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="info-footer">
                    <h4 class="dept">Rabies Care Information</h4>
                    <p class="text-muted small">Access quick links and resources for rabies prevention, bite reporting, and vaccination schedules.</p>
                </div>
            </div>

            <div class="card shadow-sm mt-4 contact-card">
                <h4 class="section-title">Emergency Contact</h4>
                <p class="text-muted">
                    If you've been exposed to an animal bite, contact the <strong>Rabies Hotline</strong> at 
                    <strong class="hotline">+1 (800) 555-1212</strong> or visit the nearest clinic immediately.
                </p>
            </div>
        </div>

        <!-- Right Column: Announcements -->
        <div>
            <h2 class="section-title">Announcements</h2>

            <div class="announcement-card">
                <div class="ann-header">
                    <div class="ann-title">Rabies Vaccination Drive — Walk-in Clinics</div>
                    <div class="ann-meta text-muted">10/20/2025 • Public Health</div>
                </div>
                <p class="ann-body">Free rabies vaccination clinics are available this month. Walk-ins are welcome from 9:00 AM to 4:00 PM at the main clinic.</p>
            </div>

            <div class="announcement-card">
                <div class="ann-header">
                    <div class="ann-title">How to Report an Animal Bite</div>
                    <div class="ann-meta text-muted">09/30/2025 • Rabies Unit</div>
                </div>
                <p class="ann-body">If bitten or scratched, wash the wound for 15 minutes, seek medical evaluation, and report it via the “Report Bite” option under Cases.</p>
            </div>

            <div class="announcement-card">
                <div class="ann-header">
                    <div class="ann-title">Rabies FAQs</div>
                    <div class="ann-meta text-muted">08/15/2025 • Education</div>
                </div>
                <p class="ann-body">Learn about prevention, vaccine schedules, and common myths. Visit the Rabies Resources page for guides and infographics.</p>
            </div>
        </div>
    </div>
</div>
@endsection
