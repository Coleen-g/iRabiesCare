@extends('user.layout')

@section('title', 'Dashboard')

@section('content')
<style>
  .dashboard-container {
    padding: 2.5rem 0 2rem 0;
  }
  .dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2.5rem;
  }
  .card {
    background: linear-gradient(120deg, #e8f5e9 0%, #a8e063 100%);
    border-radius: 16px;
    box-shadow: 0 4px 18px rgba(67,160,71,0.10);
    padding: 2rem 1.7rem 1.7rem 1.7rem;
    transition: all 0.3s cubic-bezier(.4,2,.6,1);
    position: relative;
    overflow: hidden;
  }
  .card:before {
    content: '';
    position: absolute;
    top: -40px; left: -40px;
    width: 120px; height: 120px;
    background: radial-gradient(circle, #43a047 0%, transparent 70%);
    opacity: 0.10;
    z-index: 0;
  }
  .card:hover {
    transform: translateY(-4px) scale(1.01);
    box-shadow: 0 8px 24px rgba(67,160,71,0.16);
  }
  .profile-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.7rem;
  }
  .profile-header .profile-name {
    font-size: 1.7rem;
    font-weight: 700;
    color: #43a047;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .profile-header .text-muted {
    font-size: 1.05rem;
    color: #66bb6a;
    font-weight: 500;
  }
  .divider {
    height: 2px;
    background: linear-gradient(90deg, #43a047 0%, #a8e063 100%);
    margin: 1.5rem 0 1.2rem 0;
    border: none;
    border-radius: 2px;
  }
  .section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #43a047;
    margin-bottom: 1.1rem;
    letter-spacing: 0.2px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .section-title i {
    color: #a8e063;
    font-size: 1.2rem;
  }
  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.1rem 2.2rem;
    margin-bottom: 0.7rem;
  }
  .info-item {
    font-size: 1.05rem;
    margin-bottom: 0.3rem;
    color: #222;
  }
  .info-item span {
    font-weight: 700;
    color: #43a047;
  }
  .text-muted {
    color: #66bb6a;
    font-size: 1.01rem;
  }
  .sub-title {
    font-weight: 700;
    margin-bottom: 0.3rem;
    color: #43a047;
    font-size: 1.08rem;
  }
  .info-footer {
    background: linear-gradient(90deg, #43a047 0%, #a8e063 100%);
    color: #fff;
    border-radius: 12px;
    padding: 1.2rem 1rem 1rem 1rem;
    text-align: center;
    margin-top: 1.5rem;
    box-shadow: 0 2px 8px rgba(67,160,71,0.10);
  }
  .info-footer .dept {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
    letter-spacing: 0.2px;
  }
  .info-footer p {
    font-size: 1.01rem;
    color: #e8f5e9;
    margin-bottom: 0;
  }
  /* Announcements */
  .announcement-card {
    background: linear-gradient(120deg, #e8f5e9 0%, #a8e063 100%);
    border-left: 6px solid #43a047;
    border-radius: 12px;
    padding: 1.2rem 1.5rem 1.1rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(67,160,71,0.08);
    transition: 0.3s cubic-bezier(.4,2,.6,1);
    position: relative;
    overflow: hidden;
  }
  .announcement-card:before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 80px; height: 80px;
    background: radial-gradient(circle, #43a047 0%, transparent 70%);
    opacity: 0.08;
    z-index: 0;
  }
  .announcement-card:hover {
    transform: translateY(-3px) scale(1.01);
    border-color: #a8e063;
    box-shadow: 0 6px 18px rgba(67,160,71,0.13);
  }
  .ann-title {
    font-weight: 700;
    font-size: 1.08rem;
    color: #43a047;
    margin-bottom: 0.2rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
  }
  .ann-title i {
    color: #a8e063;
    font-size: 1.1rem;
  }
  .ann-meta {
    font-size: 0.92rem;
    color: #66bb6a;
    margin-bottom: 0.5rem;
    font-weight: 500;
  }
  .ann-body {
    font-size: 1.05rem;
    color: #1a1a1a;
    margin-bottom: 0;
  }
  /* Emergency Contact */
  .contact-card {
    background: linear-gradient(90deg, #43a047 0%, #a8e063 100%);
    color: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(67,160,71,0.10);
    margin-top: 1.5rem;
    padding: 1.3rem 1.2rem 1.1rem 1.2rem;
    position: relative;
    overflow: hidden;
  }
  .contact-card:before {
    content: '';
    position: absolute;
    top: -30px; left: -30px;
    width: 80px; height: 80px;
    background: radial-gradient(circle, #fff 0%, transparent 70%);
    opacity: 0.08;
    z-index: 0;
  }
  .contact-card h4 {
    color: #fff;
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 0.7rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
  }
  .hotline {
    color: #fff176;
    font-weight: bold;
    font-size: 1.1rem;
    letter-spacing: 0.5px;
    background: #43a047;
    padding: 0.2rem 0.7rem;
    border-radius: 6px;
    margin-left: 0.5rem;
    box-shadow: 0 1px 4px rgba(67,160,71,0.10);
  }
  @media (max-width: 900px) {
    .dashboard-grid {
      grid-template-columns: 1fr;
      gap: 2rem;
    }
  }
</style>

<div class="dashboard-container">
  <div class="dashboard-grid">

    <!-- Left Column -->
    <div>
      <div class="card profile-card">
        <div class="profile-header">
          <div>
            <div class="text-muted"><i class="bi bi-hand-thumbs-up"></i> Welcome back,</div>
            <div class="profile-name">
              <i class="bi bi-person-circle"></i> {{ optional(optional(auth()->user())->patient)->name ?? auth()->user()->name }}
            </div>
          </div>
        </div>
        <div class="divider"></div>
        <div class="profile-section">
          <h4 class="section-title"><i class="bi bi-info-circle"></i> Patient Information</h4>
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
              <h5 class="sub-title"><i class="bi bi-geo-alt"></i> Address</h5>
              <p class="text-muted">{{ $patient->address ?? '—' }}</p>
            </div>
            <div>
              <h5 class="sub-title"><i class="bi bi-telephone"></i> Emergency Contact</h5>
              <p class="text-muted">{{ $patient->emergency_contact ?? '—' }}</p>
            </div>
          </div>
          <div class="info-grid">
            <div>
              <h5 class="sub-title"><i class="bi bi-exclamation-triangle"></i> Exposure Details</h5>
              <p class="text-muted">Date: {{ $patient->exposure_date ? \Illuminate\Support\Carbon::parse($patient->exposure_date)->format('Y-m-d') : '—' }}</p>
              <p class="text-muted">Type: {{ $patient->exposure_type ?? '—' }}</p>
              <p class="text-muted">Animal: {{ $patient->animal ?? '—' }}</p>
            </div>
            <div>
              <h5 class="sub-title"><i class="bi bi-shield-check"></i> Vaccination Details</h5>
              <p class="text-muted">Status: {{ $patient->vaccination_status ?? '—' }}</p>
              <p class="text-muted">Last Dose: {{ $patient->last_dose_date ? \Illuminate\Support\Carbon::parse($patient->last_dose_date)->format('Y-m-d') : '—' }}</p>
            </div>
          </div>
        </div>
        <div class="divider"></div>
        <div class="info-footer">
          <h4 class="dept"><i class="bi bi-heart-pulse"></i> Rabies Care Information</h4>
          <p>Access quick links and resources for rabies prevention, bite reporting, and vaccination schedules.</p>
        </div>
      </div>
      <div class="contact-card mt-4">
        <h4 class="section-title"><i class="bi bi-telephone-forward"></i> Emergency Contact</h4>
        <p>If you’ve been exposed to an animal bite, contact the <strong>Rabies Hotline</strong> at
          <span class="hotline"><i class="bi bi-telephone"></i> +1 (800) 555-1212</span> or visit the nearest clinic immediately.
        </p>
      </div>
    </div>

    <!-- Right Column -->
    <div>
      <h2 class="section-title"><i class="bi bi-megaphone"></i> Announcements</h2>

      <div class="announcement-card">
        <div class="ann-header">
          <div class="ann-title"><i class="bi bi-capsule"></i> Rabies Vaccination Drive — Walk-in Clinics</div>
          <div class="ann-meta">10/20/2025 • Public Health</div>
        </div>
        <p class="ann-body">Free rabies vaccination clinics are available this month. Walk-ins are welcome from 9:00 AM to 4:00 PM at the main clinic.</p>
      </div>

      <div class="announcement-card">
        <div class="ann-header">
          <div class="ann-title"><i class="bi bi-flag"></i> How to Report an Animal Bite</div>
          <div class="ann-meta">09/30/2025 • Rabies Unit</div>
        </div>
        <p class="ann-body">If bitten or scratched, wash the wound for 15 minutes, seek medical evaluation, and report it via the “Report Bite” option under Cases.</p>
      </div>

      <div class="announcement-card">
        <div class="ann-header">
          <div class="ann-title"><i class="bi bi-question-circle"></i> Rabies FAQs</div>
          <div class="ann-meta">08/15/2025 • Education</div>
        </div>
        <p class="ann-body">Learn about prevention, vaccine schedules, and common myths. Visit the Rabies Resources page for guides and infographics.</p>
      </div>
    </div>
  </div>
</div>
@endsection
