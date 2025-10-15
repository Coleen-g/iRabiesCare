@extends('user.layout')

@section('title','Dashboard')

@section('content')
    <!-- styles are loaded from resources/css/user.css -->

    <div class="dashboard-grid">
        <div>
            <div class="profile-card card">
                <div class="profile-header">
                    <img src="/images/logoO.png" alt="logo" style="width:84px;height:auto" />
                    <div>
                        <div class="muted">Welcome</div>
                        <div class="profile-name">{{ auth()->user()->name }}</div>
                    </div>
                </div>

                <div style="margin-top:.75rem">
                    <div class="profile-sub">Patient ID: <strong>{{ $patient->id_number ?? ($patient->id ?? '—') }}</strong></div>
                    <div class="profile-contact">Phone: {{ $patient->contact ?? '—' }}</div>
                </div>

                <div class="dept">Rabies Care Information</div>
                <div class="muted" style="margin-top:.5rem">Quick links and resources for rabies prevention, reporting bites and vaccination scheduling.</div>
            </div>

            <div class="card" style="margin-top:1rem">
                <h4 style="margin-top:0">Contact</h4>
                <p class="muted">If you've been exposed to an animal bite, contact the Rabies Hotline at <strong>+1 (800) 555-1212</strong> or visit the nearest clinic immediately.</p>
            </div>
        </div>

        <div>
            <h2 style="margin-top:0">Announcements</h2>

            <div class="ann-card">
                <div class="ann-title">Rabies Vaccination Drive — Walk-in Clinics</div>
                <div class="muted">10/20/2025 • Public Health</div>
                <div class="ann-body" style="margin-top:.75rem">We are running free rabies vaccination clinics this month. Patients who are due for rabies vaccine or post-exposure prophylaxis (PEP) can walk in between 9:00 AM and 4:00 PM at the main clinic.</div>
            </div>

            <div class="ann-card">
                <div class="ann-title">How to report an animal bite</div>
                <div class="muted">09/30/2025 • Rabies Unit</div>
                <div class="ann-body" style="margin-top:.75rem">If bitten or scratched by an animal, wash the wound thoroughly with soap and water for 15 minutes, seek medical evaluation immediately, and report the incident via the "Report Bite" link under Cases.</div>
            </div>

            <div class="ann-card">
                <div class="ann-title">Rabies FAQs</div>
                <div class="muted">08/15/2025 • Education</div>
                <div class="ann-body" style="margin-top:.75rem">Learn about prevention, vaccine schedules, and myths surrounding rabies. Visit the Rabies Resources page for guides and infographics.</div>
            </div>
        </div>
    </div>
@endsection
