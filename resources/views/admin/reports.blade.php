@extends('admin.layout')

@section('title','Reports')

@section('content')
<!-- Load Font Awesome so the `fa-` icons used in this view render correctly -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    /* Reuse admin dashboard styling patterns for a consistent look */
    body { background: #f5f6f7; font-family: "Inter", system-ui, sans-serif; }
    .edit-card { max-width: 1200px; margin: 2.5rem auto; background: #fff; border-radius: 14px; padding: 1.8rem; border:1px solid #e5e7eb; box-shadow:0 8px 20px rgba(0,0,0,0.04); }
    .page-header{ display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.4rem }
    .page-title{ display:flex; align-items:center; gap:1rem }
    .page-title i{ background:#000; color:#fff; padding:0.6rem; border-radius:10px; font-size:1.1rem }
    h2.page{ margin:0; font-size:1.25rem; font-weight:700; color:#111827 }
    p.intro-text{ color:#4b5563; margin-bottom:1rem }

    .section-row { display:grid; grid-template-columns: repeat(3,1fr); gap:1rem; margin-bottom:1rem }
    @media (max-width:1100px){ .section-row { grid-template-columns: repeat(2,1fr) } }
    @media (max-width:700px){ .section-row { grid-template-columns: 1fr } }

    .stat-card { background: linear-gradient(180deg,#ffffff,#fbfdff); border-radius: 12px; padding:1rem; border:1px solid #e5e7eb; box-shadow:0 4px 10px rgba(0,0,0,0.03); }
    .stat-card h4{ margin:0 0 0.4rem 0; font-size:0.98rem }
    .stat-value{ font-size:1.5rem; font-weight:700; color:#111827 }
    .stat-meta{ color:#6b7280; font-size:0.9rem }

    .card-flex { display:flex; gap:1rem; align-items:center }
    .card-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:white }
    .icon-primary{ background:linear-gradient(135deg,#000000,#374151) }
    .icon-accent{ background:linear-gradient(135deg,#3b82f6,#1e40af) }

    /* Section header and separators */
    .section-title { display:flex; align-items:center; gap:0.6rem; font-weight:700; color:#111827; margin-bottom:0.6rem }
    .section-title i { background:#f3f4f6; color:#111; padding:0.55rem; border-radius:8px; font-size:1rem }
    .reports-section { padding-top:1rem; margin-top:1rem; border-top:1px solid #eef2f7 }

    .stat-card:hover { transform:translateY(-4px); box-shadow:0 12px 24px rgba(2,6,23,0.06) }
    .stat-card .card-icon i { font-size:1.05rem }

    .section-actions { display:flex; gap:0.5rem; align-items:center }
    .section-actions .btn-secondary, .section-actions .btn-primary { font-size:0.9rem; padding:.4rem .6rem }

    .chart-wrap { background:#fff; border-radius:12px; padding:0.9rem; border:1px solid #e5e7eb; box-shadow:0 4px 8px rgba(0,0,0,0.02) }
    .chart-title{ font-weight:600; color:#111827; margin-bottom:0.6rem }
    .small-list{ list-style:none; padding:0; margin:0 } .small-list li{ padding:0.25rem 0; color:#374151 }

    .map-placeholder{ min-height:200px; border-radius:8px; background:linear-gradient(135deg,#f8fafc,#fff); display:flex; align-items:center; justify-content:center; color:#6b7280 }

</style>

<div class="edit-card">
    <div class="page-header">
        <div class="page-title">
            <i class="fa-solid fa-chart-pie"></i>
            <h2 class="page">Reports & Analytics</h2>
        </div>
        <div>
            <a href="?refresh=1" class="btn-secondary">
                <i class="fa-solid fa-arrows-rotate"></i> Refresh
            </a>
        </div>
    </div>

    <p class="intro-text">Overview and downloadable reports for cases, vaccinations, patients and animal bites. Charts are placeholders wired for controller-provided data arrays.</p>

    <!-- 1. Rabies Case Reports -->
    <div class="reports-section" style="margin-bottom:1.6rem">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div class="section-title"><i class="fa-solid fa-notes-medical" aria-hidden="true"></i> Rabies Case Reports</div>
            <div class="section-actions">
                <a href="#" class="btn-secondary"><i class="fa-solid fa-file-csv"></i> CSV</a>
                <a href="#" class="btn-primary"><i class="fa-solid fa-file-pdf"></i> PDF</a>
            </div>
        </div>

        <div class="section-row" style="margin-top:0.8rem">
            <div class="stat-card">
                <div class="card-flex">
                    <div class="card-icon icon-primary"><i class="fa-solid fa-virus"></i></div>
                    <div>
                        <h4>Total Cases</h4>
                        <div class="stat-value">{{ $totalCases ?? 0 }}</div>
                        <div class="stat-meta">(Selected period)</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <h4>Cases by Status</h4>
                <ul class="small-list">
                    @foreach($casesByStatus ?? ['Active'=>0,'Under Investigation'=>0,'Resolved'=>0,'Deceased'=>0] as $status => $count)
                        <li><strong>{{ $status }}</strong>: {{ $count }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="stat-card">
                <h4>Cases by Exposure Type</h4>
                <ul class="small-list">
                    @foreach($casesByExposure ?? ['Bite'=>0,'Scratch'=>0,'Lick'=>0] as $type => $count)
                        <li>{{ $type }}: {{ $count }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="section-row" style="margin-top:0.8rem">
            <div class="chart-wrap">
                <div class="chart-title">New Cases per Month</div>
                <canvas id="casesLineChart"></canvas>
            </div>

            <div class="chart-wrap">
                <div class="chart-title">Cases by Animal Type</div>
                <canvas id="casesByAnimalPie"></canvas>
            </div>

            <div class="chart-wrap">
                <div class="chart-title">Cases by Barangay (map)</div>
                <div class="map-placeholder">Map placeholder — integrate Leaflet/Mapbox and supply `casesByBarangay` data</div>
            </div>
        </div>
    </div>

    <!-- 2. Vaccination Reports -->
    <div class="reports-section" style="margin-bottom:1.6rem">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div class="section-title"><i class="fa-solid fa-syringe" aria-hidden="true"></i> Vaccination Reports</div>
            <div class="section-actions">
                <a href="#" class="btn-secondary"><i class="fa-solid fa-file-csv"></i> CSV</a>
                <a href="#" class="btn-primary"><i class="fa-solid fa-file-pdf"></i> PDF</a>
            </div>
        </div>

        <div class="section-row" style="margin-top:0.8rem">
            <div class="stat-card">
                <div class="card-flex">
                    <div class="card-icon icon-accent"><i class="fa-solid fa-user-check"></i></div>
                    <div>
                        <h4>Total Vaccinated</h4>
                        <div class="stat-value">{{ $totalVaccinated ?? 0 }}</div>
                        <div class="stat-meta">Population coverage: {{ $vaccinationCoverage ?? '0%' }}</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <h4>Schedules: Completed vs Ongoing</h4>
                <ul class="small-list">
                    <li>Completed: {{ $completedSchedules ?? 0 }}</li>
                    <li>Ongoing: {{ $ongoingSchedules ?? 0 }}</li>
                    <li>Missed follow-ups: {{ $missedFollowups ?? 0 }}</li>
                </ul>
            </div>

            <div class="stat-card">
                <h4>Vaccine Inventory</h4>
                <ul class="small-list">
                    @foreach($vaccineInventory ?? ['Total Doses'=>0,'Used'=>0,'Remaining'=>0] as $k=>$v)
                        <li>{{ $k }}: {{ $v }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="section-row" style="margin-top:0.8rem">
            <div class="chart-wrap">
                <div class="chart-title">Vaccines Administered per Week</div>
                <canvas id="vaccinesPerWeekBar"></canvas>
            </div>

            <div class="chart-wrap">
                <div class="chart-title">Completed vs Pending (Donut)</div>
                <canvas id="vaccinationDonut"></canvas>
            </div>

            <div class="chart-wrap">
                <div class="chart-title">Coverage by Area</div>
                <ul class="small-list">
                    @foreach($coverageByArea ?? [] as $area => $pct)
                        <li>{{ $area }}: {{ $pct }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- 3. Patient Reports -->
    <div class="reports-section" style="margin-bottom:1.6rem">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div class="section-title"><i class="fa-solid fa-users" aria-hidden="true"></i> Patient Reports</div>
            <div class="section-actions">
                <a href="#" class="btn-secondary"><i class="fa-solid fa-file-csv"></i> CSV</a>
                <a href="#" class="btn-primary"><i class="fa-solid fa-file-pdf"></i> PDF</a>
            </div>
        </div>

        <div class="section-row" style="margin-top:0.8rem">
            <div class="stat-card">
                <h4>Patients Treated (period)</h4>
                <div class="stat-value">{{ $patientsTreated ?? 0 }}</div>
                <div class="stat-meta">This month / year totals</div>
            </div>

            <div class="stat-card">
                <h4>Demographics</h4>
                <ul class="small-list">
                    @foreach($patientDemographics ?? ['0-10'=>0,'11-20'=>0,'21-40'=>0,'41+'=>0] as $k=>$v)
                        <li>{{ $k }}: {{ $v }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="stat-card">
                <h4>Repeat Patients & Follow-up</h4>
                <ul class="small-list">
                    <li>Repeat cases: {{ $repeatPatients ?? 0 }}</li>
                    <li>Follow-up compliance: {{ $followupCompliance ?? '0%' }}</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- 4. Animal Bite Reports -->
    <div class="reports-section" style="margin-bottom:0.6rem">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div class="section-title"><i class="fa-solid fa-paw" aria-hidden="true"></i> Animal Bite Reports</div>
            <div class="section-actions">
                <a href="#" class="btn-secondary"><i class="fa-solid fa-file-csv"></i> CSV</a>
                <a href="#" class="btn-primary"><i class="fa-solid fa-file-pdf"></i> PDF</a>
            </div>
        </div>

        <div class="section-row" style="margin-top:0.8rem">
            <div class="stat-card">
                <h4>Bites by Species</h4>
                <ul class="small-list">
                    @foreach($bitesBySpecies ?? ['Dog'=>0,'Cat'=>0,'Bat'=>0] as $species=>$count)
                        <li>{{ $species }}: {{ $count }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="stat-card">
                <h4>Severity Level</h4>
                <ul class="small-list">
                    <li>Category I: {{ $cat1 ?? 0 }}</li>
                    <li>Category II: {{ $cat2 ?? 0 }}</li>
                    <li>Category III: {{ $cat3 ?? 0 }}</li>
                </ul>
            </div>

            <div class="stat-card">
                <h4>Animals Vaccinated vs Unvaccinated</h4>
                <ul class="small-list">
                    <li>Vaccinated: {{ $animalsVaccinated ?? 0 }}</li>
                    <li>Unvaccinated: {{ $animalsUnvaccinated ?? 0 }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div style="margin-top:1rem; display:flex; gap:0.6rem; justify-content:flex-end">
        <a href="#" class="btn-secondary"><i class="fa-solid fa-file-csv"></i> Export CSV</a>
        <a href="#" class="btn-primary"><i class="fa-solid fa-file-pdf"></i> Export PDF</a>
    </div>
</div>

<!-- Chart.js and initialization: controllers should pass arrays for these variables -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Helper to parse Blade-provided arrays or fallback
    const casesPerMonth = {!! json_encode($casesPerMonth ?? ['Jan'=>5,'Feb'=>8,'Mar'=>12]) !!};
    const casesByAnimal = {!! json_encode($casesByAnimal ?? ['Dog'=>50,'Cat'=>10,'Bat'=>2]) !!};
    const vaccinesPerWeek = {!! json_encode($vaccinesPerWeek ?? ['W1'=>30,'W2'=>45,'W3'=>25]) !!};
    const vaccinationSummary = {!! json_encode($vaccinationSummary ?? ['Completed'=>120,'Pending'=>30]) !!};

    // Line chart: cases per month
    (function(){
        const ctx = document.getElementById('casesLineChart');
        if(!ctx) return;
        new Chart(ctx, {
            type: 'line',
            data: { labels: Object.keys(casesPerMonth), datasets: [{ label: 'New cases', data: Object.values(casesPerMonth), borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.08)', tension: 0.2 }] },
            options: { responsive:true, plugins:{legend:{display:false}} }
        });
    })();

    // Pie chart: cases by animal
    (function(){
        const ctx = document.getElementById('casesByAnimalPie');
        if(!ctx) return;
        new Chart(ctx, {
            type: 'pie',
            data: { labels: Object.keys(casesByAnimal), datasets:[{ data: Object.values(casesByAnimal), backgroundColor:['#6366f1','#f97316','#10b981','#e11d48'] }] },
            options:{ responsive:true }
        });
    })();

    // Bar chart: vaccines per week
    (function(){
        const ctx = document.getElementById('vaccinesPerWeekBar');
        if(!ctx) return;
        new Chart(ctx, {
            type: 'bar',
            data: { labels: Object.keys(vaccinesPerWeek), datasets:[{ label:'Doses', data: Object.values(vaccinesPerWeek), backgroundColor:'#3b82f6' }] },
            options:{ responsive:true, plugins:{legend:{display:false}} }
        });
    })();

    // Donut chart: vaccination completed vs pending
    (function(){
        const ctx = document.getElementById('vaccinationDonut');
        if(!ctx) return;
        new Chart(ctx, {
            type: 'doughnut',
            data:{ labels: Object.keys(vaccinationSummary), datasets:[{ data: Object.values(vaccinationSummary), backgroundColor:['#10b981','#f59e0b'] }] },
            options:{ responsive:true }
        });
    })();
</script>

@endsection
