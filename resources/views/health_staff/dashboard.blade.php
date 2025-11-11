@extends('health_staff.layout')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Reused admin dashboard styles for consistent UI */
    .dashboard-container { padding: 1.5rem; max-width: 1400px; margin: 0 auto; width: 100%; }
    .dashboard-header { margin-bottom: 2rem; display:flex; justify-content:space-between; align-items:center }
    .header-title { display:flex; align-items:center; gap:0.75rem }
    .header-title h1 { font-size:1.75rem; font-weight:700; color:#111827; margin:0 }
    .header-icon { width:3rem; height:3rem; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#000,#2c2c2c); border-radius:12px; color:white; font-size:1.25rem }

    .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; margin-bottom:2rem }
    @media(max-width:1200px){ .stats-grid{ grid-template-columns:repeat(2,1fr) } }
    @media(max-width:768px){ .stats-grid{ grid-template-columns:1fr } }

    .stat-card { background:linear-gradient(180deg,#ffffff,#fbfdff); border-radius:16px; padding:1.5rem; position:relative; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); border:1px solid #e5e7eb }
    .stat-card::before{ content:''; position:absolute; left:0; top:0; bottom:0; width:6px; border-top-left-radius:16px; border-bottom-left-radius:16px }
    .stat-card.primary::before{ background:linear-gradient(180deg,#000,#374151) }
    .stat-card.info::before{ background:linear-gradient(180deg,#3b82f6,#1e40af) }
    .stat-card.success::before{ background:linear-gradient(180deg,#10b981,#047857) }

    .stat-card-link{ text-decoration:none; color:inherit; display:block; border-radius:16px }
    .stat-card-link:hover .stat-card{ transform:translateY(-6px); box-shadow:0 16px 30px -12px rgba(2,6,23,0.2); border-color:#d1d5db }

    .stat-header{ display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.25rem; position:relative; z-index:1 }
    .stat-icon{ width:3rem; height:3rem; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); transition:transform .3s }
    .stat-card:hover .stat-icon{ transform:scale(1.1) }
    .stat-content{ position:relative; z-index:1 }
    .stat-label{ font-size:.875rem; font-weight:600; color:#6b7280; margin-bottom:.5rem }
    .stat-value{ font-size:2rem; font-weight:700; color:#111827; margin-bottom:1rem }
    .stat-trend{ display:inline-flex; align-items:center; gap:.5rem; padding:.375rem .75rem; border-radius:9999px; font-size:.875rem; font-weight:500 }

    .stat-icon.primary{ background:linear-gradient(135deg,#000,#2c2c2c); color:#fff }
    .stat-icon.info{ background:linear-gradient(135deg,#3b82f6,#1d4ed8); color:#fff }
    .stat-icon.success{ background:linear-gradient(135deg,#10b981,#047857); color:#fff }

    .overview-section{ background:white; border-radius:16px; padding:1.5rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); border:1px solid #e5e7eb }
    .overview-header{ display:flex; align-items:center; gap:.75rem; margin-bottom:1.5rem }
    .overview-header h2{ font-size:1.25rem; font-weight:600; color:#111827; margin:0 }
    .overview-header .icon{ width:2.5rem; height:2.5rem; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#000,#2c2c2c); border-radius:10px; color:white }
    .activity-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:1rem }
    @media(max-width:768px){ .activity-grid{ grid-template-columns:1fr } }
    .activity-item{ padding:1.25rem; background:#f9fafb; border-radius:12px; text-align:center }
    .activity-icon{ width:2.5rem; height:2.5rem; display:flex; align-items:center; justify-content:center; margin:0 auto .75rem; font-size:1.25rem }
    .activity-value{ font-size:1.5rem; font-weight:700; color:#111827 }

    .recent-section{ background:white; border-radius:16px; padding:1.5rem; margin-top:2rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); border:1px solid #e5e7eb }
    .section-header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem }
    .view-all{ color:#2563eb; text-decoration:none; font-size:.875rem; font-weight:500; display:flex; align-items:center; gap:.5rem }
    .status-badge{ display:inline-block; padding:.25rem .75rem; border-radius:9999px; font-size:.75rem; font-weight:500 }
    .status-badge.active{ background:#dcfce7; color:#166534 }
    .status-badge.pending{ background:#fef3c7; color:#92400e }
    .status-badge.completed{ background:#dbeafe; color:#1e40af }
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-title">
            <div class="header-icon"><i class="bi bi-speedometer2"></i></div>
            <h1>Staff Dashboard</h1>
        </div>
    </div>

    <div class="stats-grid">
        <a href="{{ route('health_staff.patients.index') }}" class="stat-card-link" aria-label="View patients">
            <div class="stat-card primary">
                <div class="stat-header">
                    <div class="stat-icon primary" aria-hidden="true"><i class="bi bi-people"></i></div>
                    <div class="stat-meta"><small style="color:#6b7280;font-weight:600;">Patients</small></div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Assigned Patients</div>
                    <div class="stat-value">{{ $myPatientsCount ?? 0 }}</div>
                    <div class="stat-trend primary"><i class="bi bi-person-plus"></i><span>Current Cases</span></div>
                </div>
            </div>
        </a>

        <a href="{{ route('health_staff.cases.index') }}" class="stat-card-link" aria-label="View cases">
            <div class="stat-card info">
                <div class="stat-header">
                    <div class="stat-icon info" aria-hidden="true"><i class="bi bi-journal-medical"></i></div>
                    <div class="stat-meta"><small style="color:#6b7280;font-weight:600;">Cases</small></div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Active Cases</div>
                    <div class="stat-value">{{ $activeCasesCount ?? 0 }}</div>
                    <div class="stat-trend info"><i class="bi bi-clipboard-data"></i><span>In Treatment</span></div>
                </div>
            </div>
        </a>

        <a href="{{ route('health_staff.vaccinations.index') }}" class="stat-card-link" aria-label="View vaccinations">
            <div class="stat-card success">
                <div class="stat-header">
                    <div class="stat-icon success" aria-hidden="true"><i class="bi bi-capsule"></i></div>
                    <div class="stat-meta"><small style="color:#6b7280;font-weight:600;">Vaccinations</small></div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Completed Vaccinations</div>
                    <div class="stat-value">{{ $completedVaccinationsCount ?? 0 }}</div>
                    <div class="stat-trend success"><i class="bi bi-check-circle"></i><span>Completed</span></div>
                </div>
            </div>
        </a>
    </div>

    <script>
        (function(){
            const url = "{{ route('health_staff.dashboard.stats') }}";
            const selectors = {
                patients: document.getElementById('stat-patients'),
                cases: document.getElementById('stat-cases'),
                vaccinations: document.getElementById('stat-vaccinations'),
                todayAppointments: document.getElementById('stat-today-appointments'),
                todayVaccinations: document.getElementById('stat-today-vaccinations'),
                todayNewCases: document.getElementById('stat-today-newcases')
            };

            async function refresh(){
                try{
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if(!res.ok) return;
                    const data = await res.json();
                    // update only if fields exist in response
                    if(data.myPatientsCount !== undefined && document.querySelectorAll('.stat-value').length) {
                        // small DOM sync: replace KPI values if present
                        const patientsEl = document.querySelectorAll('.stat-card.primary .stat-value')[0];
                        const casesEl = document.querySelectorAll('.stat-card.info .stat-value')[0];
                        const vaccEl = document.querySelectorAll('.stat-card.success .stat-value')[0];
                        if(patientsEl) patientsEl.textContent = data.myPatientsCount ?? patientsEl.textContent;
                        if(casesEl) casesEl.textContent = data.activeCasesCount ?? casesEl.textContent;
                        if(vaccEl) vaccEl.textContent = data.completedVaccinationsCount ?? vaccEl.textContent;
                    }
                    if (selectors.todayAppointments) selectors.todayAppointments.textContent = data.todayAppointments ?? selectors.todayAppointments.textContent;
                    if (selectors.todayVaccinations) selectors.todayVaccinations.textContent = data.todayVaccinations ?? selectors.todayVaccinations.textContent;
                    if (selectors.todayNewCases) selectors.todayNewCases.textContent = data.todayNewCases ?? selectors.todayNewCases.textContent;
                }catch(err){ console.debug('Failed to refresh dashboard stats', err) }
            }

            document.addEventListener('DOMContentLoaded', function(){ refresh(); setInterval(refresh, 10000); });
        })();
    </script>

    <div class="overview-section">
        <div class="overview-header">
            <div class="icon"><i class="bi bi-graph-up"></i></div>
            <h2>Today's Activity</h2>
        </div>

        <div class="activity-grid">
            <div class="activity-item">
                <div class="activity-icon" style="color:#2563eb"><i class="bi bi-calendar-check"></i></div>
                <div id="stat-today-appointments" class="activity-value">{{ $todayAppointments ?? 0 }}</div>
                <div class="activity-label">Appointments</div>
            </div>

            <div class="activity-item">
                <div class="activity-icon" style="color:#16a34a"><i class="bi bi-capsule"></i></div>
                <div id="stat-today-vaccinations" class="activity-value">{{ $todayVaccinations ?? 0 }}</div>
                <div class="activity-label">Vaccinations</div>
            </div>

            <div class="activity-item">
                <div class="activity-icon" style="color:#dc2626"><i class="bi bi-exclamation-triangle"></i></div>
                <div id="stat-today-newcases" class="activity-value">{{ $todayNewCases ?? 0 }}</div>
                <div class="activity-label">New Cases</div>
            </div>
        </div>
    </div>

    <div class="recent-section">
        <div class="section-header">
            <h2>Recent Cases</h2>
            <a href="{{ route('health_staff.cases.index') }}" class="view-all">View All Cases <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Case ID</th>
                        <th>Patient Name</th>
                        <th>Bite Location</th>
                        <th>Report Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCases ?? [] as $case)
                    <tr>
                        <td>#{{ $case->id }}</td>
                        <td>{{ optional($case->patient)->name }}</td>
                        <td>{{ $case->bite_location }}</td>
                        <td>{{ $case->report_date ? $case->report_date->format('M d, Y') : '—' }}</td>
                        <td><span class="status-badge {{ strtolower($case->status) }}">{{ $case->status }}</span></td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('health_staff.cases.show', $case->id) }}" class="btn-action view" title="View Case"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('health_staff.cases.edit', $case->id) }}" class="btn-action edit" title="Update Case"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="empty-state-content">
                                <i class="bi bi-inbox"></i>
                                <p>No recent cases</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection