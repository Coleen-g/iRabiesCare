@extends('admin.layout')

@section('title','Reports')

@section('content')
<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafb;
    }

    .reports-container {
        max-width: 1000px;
        margin: 2rem auto;
        background: #fff;
        border-radius: 12px;
        padding: 2rem 2.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .reports-header {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        border-bottom: 3px solid #2563eb;
        padding-bottom: 0.7rem;
        margin-bottom: 1.5rem;
    }

    .reports-header i {
        color: #2563eb;
        font-size: 1.5rem;
    }

    .reports-header h2 {
        font-size: 1.6rem;
        color: #1f2937;
        margin: 0;
        font-weight: 700;
    }

    .reports-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2563eb;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .report-card {
        background-color: #f3f4f6;
        border-radius: 10px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 1rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }

    .report-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .report-card h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #111827;
        margin: 0 0 0.4rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .report-card p {
        color: #4b5563;
        font-size: 0.9rem;
        margin: 0;
    }

    .report-card i {
        color: #2563eb;
        font-size: 1.2rem;
    }

    .generate-btn {
        background-color: #2563eb;
        color: white;
        border: none;
        padding: 0.45rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: background-color 0.3s ease, transform 0.2s ease;
        cursor: pointer;
        margin-top: 0.6rem;
    }

    .generate-btn:hover {
        background-color: #1d4ed8;
        transform: scale(1.05);
    }

    @media (max-width: 700px) {
        .reports-container {
            padding: 1.5rem;
        }
    }
</style>

<div class="reports-container">
    <div class="reports-header">
        <i class="fa-solid fa-chart-pie"></i>
        <h2>Reports & Analytics</h2>
    </div>

    <p style="color:#4b5563;margin-bottom:1.5rem;">
        View and generate reports for rabies cases, vaccinations, and patients to monitor trends and performance.
    </p>

    {{-- Case Reports --}}
    <div class="reports-section">
        <div class="section-title"><i class="fa-solid fa-notes-medical"></i> Case Reports</div>
        <div class="report-card">
            <h3><i class="fa-solid fa-virus"></i> Rabies Case Summary</h3>
            <p>Shows all recorded rabies cases with their statuses, locations, and patient information.</p>
            <button class="generate-btn">Generate Report</button>
        </div>
        <div class="report-card">
            <h3><i class="fa-solid fa-calendar-day"></i> Monthly Case Statistics</h3>
            <p>Track the number of reported rabies cases and recoveries for each month.</p>
            <button class="generate-btn">View</button>
        </div>
    </div>

    {{-- Vaccination Reports --}}
    <div class="reports-section">
        <div class="section-title"><i class="fa-solid fa-syringe"></i> Vaccination Reports</div>
        <div class="report-card">
            <h3><i class="fa-solid fa-notes-medical"></i> Vaccination Summary</h3>
            <p>Provides an overview of all vaccinations given, including dose type, date, and administering staff.</p>
            <button class="generate-btn">Generate</button>
        </div>
        <div class="report-card">
            <h3><i class="fa-solid fa-chart-line"></i> Vaccination Trends</h3>
            <p>Displays graphical trends of vaccinations administered over time.</p>
            <button class="generate-btn">View Charts</button>
        </div>
    </div>

    {{-- Patient Reports --}}
    <div class="reports-section">
        <div class="section-title"><i class="fa-solid fa-users"></i> Patient Reports</div>
        <div class="report-card">
            <h3><i class="fa-solid fa-id-card"></i> Patient Demographics</h3>
            <p>Analyzes patient data based on age, gender, and residence for health profiling.</p>
            <button class="generate-btn">Analyze</button>
        </div>
        <div class="report-card">
            <h3><i class="fa-solid fa-file-export"></i> Export Patient Records</h3>
            <p>Download a CSV or PDF file of all registered patients in the system.</p>
            <button class="generate-btn">Export</button>
        </div>
    </div>
</div>
@endsection
