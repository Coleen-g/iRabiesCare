@extends('admin.layout')

@section('title','Reports')

@section('content')
<style>
    /* Unified admin card styling */
    body { background: #f5f6f7; font-family: "Inter", system-ui, sans-serif; }

    .edit-card {
        max-width: 1150px;
        margin: 2.5rem auto;
        background: #fff;
        border-radius: 14px;
        padding: 2.2rem 2.5rem;
        border:1px solid #e5e7eb;
        box-shadow:0 8px 20px rgba(0,0,0,0.05);
    }

    .page-header {
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:1rem;
        margin-bottom:2rem;
    }

    .page-title {
        display:flex;
        align-items:center;
        gap:1rem;
    }

    .page-title i {
        background:#000;
        color:#fff;
        padding:0.65rem;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:1.3rem;
        box-shadow:0 2px 8px rgba(0,0,0,0.08);
    }

    h2.page {
        margin:0;
        font-size:1.5rem;
        font-weight:700;
        color:#111827;
    }

    p.intro-text {
        color:#4b5563;
        margin-bottom:1.7rem;
        font-size:1rem;
    }

    .reports-section {
        margin-bottom:2.2rem;
    }

    .section-title {
        font-size:1.08rem;
        font-weight:600;
        color:#065f46;
        margin-bottom:1.1rem;
        display:flex;
        align-items:center;
        gap:0.7rem;
        letter-spacing:0.2px;
    }

    .report-card {
        background:#f9fafb;
        border-radius:12px;
        padding:1.5rem 1.7rem;
        margin-bottom:1.2rem;
        border:1px solid #e5e7eb;
        box-shadow:0 4px 10px rgba(0,0,0,0.03);
        transition:box-shadow 0.2s,transform 0.2s;
    }
    .report-card:hover {
        box-shadow:0 8px 24px rgba(0,0,0,0.08);
        transform:translateY(-2px);
    }

    .report-card h3 {
        margin:0 0 0.4rem 0;
        font-size:1.08rem;
        display:flex;
        gap:0.7rem;
        align-items:center;
        font-weight:600;
        color:#111827;
    }

    .report-card p {
        margin:0;
        color:#4b5563;
        font-size:0.97rem;
    }

    .btn-primary {
        background:#000;
        color:#fff;
        padding:0.7rem 1.3rem;
        border-radius:8px;
        border:none;
        cursor:pointer;
        font-weight:600;
        transition: background 0.2s;
        display:flex;
        align-items:center;
        gap:0.5rem;
        font-size:1rem;
    }

    .btn-primary:hover {
        background:#222;
    }

    .btn-secondary {
        background:#f3f4f6;
        color:#111;
        padding:0.7rem 1.3rem;
        border-radius:8px;
        border:none;
        text-decoration:none;
        font-weight:600;
        transition: background 0.2s;
        font-size:1rem;
    }

    .btn-secondary:hover {
        background:#e5e7eb;
    }

    @media (max-width:700px){
        .edit-card { padding:1.2rem }
        .report-card { padding:1rem }
    }
</style>

<div class="edit-card">
    <div class="page-header">
        <div class="page-title">
            <i class="fa-solid fa-chart-pie"></i>
            <h2 class="page">Reports & Analytics</h2>
        </div>
        <div>
            <a href="#" class="btn-secondary">
                <i class="fa-solid fa-arrows-rotate"></i> Refresh
            </a>
        </div>
    </div>

    <p class="intro-text">View and generate reports for rabies cases, vaccinations, and patients to monitor trends and performance.</p>

    {{-- Case Reports --}}
    <div class="reports-section">
        <div class="section-title"><i class="fa-solid fa-notes-medical"></i> Case Reports</div>
        <div class="report-card">
            <h3><i class="fa-solid fa-virus"></i> Rabies Case Summary</h3>
            <p>Shows all recorded rabies cases with their statuses, locations, and patient information.</p>
            <div style="margin-top:0.8rem">
                <button class="btn-primary"><i class="fa-solid fa-file-lines"></i> Generate Report</button>
            </div>
        </div>
        <div class="report-card">
            <h3><i class="fa-solid fa-calendar-day"></i> Monthly Case Statistics</h3>
            <p>Track the number of reported rabies cases and recoveries for each month.</p>
            <div style="margin-top:0.8rem">
                <button class="btn-primary"><i class="fa-solid fa-chart-column"></i> View</button>
            </div>
        </div>
    </div>

    {{-- Vaccination Reports --}}
    <div class="reports-section">
        <div class="section-title"><i class="fa-solid fa-syringe"></i> Vaccination Reports</div>
        <div class="report-card">
            <h3><i class="fa-solid fa-notes-medical"></i> Vaccination Summary</h3>
            <p>Provides an overview of all vaccinations given, including dose type, date, and administering staff.</p>
            <div style="margin-top:0.8rem">
                <button class="btn-primary"><i class="fa-solid fa-file-lines"></i> Generate</button>
            </div>
        </div>
        <div class="report-card">
            <h3><i class="fa-solid fa-chart-line"></i> Vaccination Trends</h3>
            <p>Displays graphical trends of vaccinations administered over time.</p>
            <div style="margin-top:0.8rem">
                <button class="btn-primary"><i class="fa-solid fa-chart-simple"></i> View Charts</button>
            </div>
        </div>
    </div>

    {{-- Patient Reports --}}
    <div class="reports-section">
        <div class="section-title"><i class="fa-solid fa-users"></i> Patient Reports</div>
        <div class="report-card">
            <h3><i class="fa-solid fa-id-card"></i> Patient Demographics</h3>
            <p>Analyzes patient data based on age, gender, and residence for health profiling.</p>
            <div style="margin-top:0.8rem">
                <button class="btn-primary"><i class="fa-solid fa-chart-pie"></i> Analyze</button>
            </div>
        </div>
        <div class="report-card">
            <h3><i class="fa-solid fa-file-export"></i> Export Patient Records</h3>
            <p>Download a CSV or PDF file of all registered patients in the system.</p>
            <div style="margin-top:0.8rem">
                <button class="btn-primary"><i class="fa-solid fa-download"></i> Export</button>
            </div>
        </div>
    </div>
</div>
@endsection
