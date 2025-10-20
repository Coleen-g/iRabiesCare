@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<style>
    /* ===== Base Layout ===== */
    body {
        background-color: #f9fafb;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .dashboard-container {
        padding: 2rem;
    }

    /* ===== Header Section ===== */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .dashboard-header h1 {
        font-size: 1.8rem;
        color: #111827;
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dashboard-header h1 i {
        color: #2563eb;
        font-size: 1.6rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        background: #e0e7ff;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        color: #1e3a8a;
        font-weight: 600;
        gap: 0.75rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    }

    .user-info i {
        font-size: 1.5rem;
        color: #1e40af;
    }

    /* ===== Cards Section ===== */
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .card {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
    }

    .card h2 {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 0.3rem;
    }

    .card p {
        font-size: 1.8rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .card-icon {
        padding: 1rem;
        border-radius: 50%;
        font-size: 1.8rem;
    }

    .card-icon.blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .card-icon.red {
        background: #fee2e2;
        color: #dc2626;
    }

    .card-icon.green {
        background: #dcfce7;
        color: #15803d;
    }

    /* ===== Overview Section ===== */
    .overview {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.5rem 2rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .overview h3 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.2rem;
        color: #111827;
        margin-bottom: 1rem;
    }

    .overview h3 i {
        color: #2563eb;
    }

    .overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.2rem;
        margin-top: 1rem;
    }

    .overview-item {
        background: #f9fafb;
        border-radius: 10px;
        text-align: center;
        padding: 1rem;
        transition: 0.3s ease;
    }

    .overview-item:hover {
        background: #eff6ff;
        transform: translateY(-3px);
    }

    .overview-item i {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .overview-item.blue i { color: #2563eb; }
    .overview-item.green i { color: #16a34a; }
    .overview-item.red i { color: #dc2626; }

    .overview-item p {
        margin: 0;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .overview-item strong {
        display: block;
        color: #111827;
        font-size: 1.2rem;
        margin-top: 0.3rem;
    }
</style>

<div class="dashboard-container">
    {{-- Header --}}
    <div class="dashboard-header">
        <h1><i class="fa-solid fa-gauge"></i> Admin Dashboard</h1>

        <div class="user-info">
            <i class="fa-solid fa-user-shield"></i>
            <div>
                <div>{{ auth()->user()->name }}</div>
                <small>{{ auth()->user()->email }}</small><br>
                <span style="font-size: 0.85rem; color: #1d4ed8;">({{ ucfirst(auth()->user()->role) }})</span>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="dashboard-cards">
        <div class="card">
            <div>
                <h2>Total Patients</h2>
                <p>{{ $patientsCount ?? 0 }}</p>
            </div>
            <div class="card-icon blue">
                <i class="fa-solid fa-user-group"></i>
            </div>
        </div>

        <div class="card">
            <div>
                <h2>Reported Cases</h2>
                <p>{{ $casesCount ?? 0 }}</p>
            </div>
            <div class="card-icon red">
                <i class="fa-solid fa-virus-covid"></i>
            </div>
        </div>

        <div class="card">
            <div>
                <h2>Vaccinations Given</h2>
                <p>{{ $vaccinationsCount ?? 0 }}</p>
            </div>
            <div class="card-icon green">
                <i class="fa-solid fa-syringe"></i>
            </div>
        </div>
    </div>

    {{-- Overview Section --}}
    <div class="overview">
        <h3><i class="fa-solid fa-chart-line"></i> System Overview</h3>
        <p style="color:#6b7280; font-size:0.9rem;">A quick look at today’s system activity:</p>

        <div class="overview-grid">
            <div class="overview-item blue">
                <i class="fa-solid fa-calendar-day"></i>
                <p>Today's Registrations</p>
                <strong>{{ $todayRegistrations ?? 0 }}</strong>
            </div>

            <div class="overview-item green">
                <i class="fa-solid fa-syringe"></i>
                <p>Vaccinations Today</p>
                <strong>{{ $todayVaccinations ?? 0 }}</strong>
            </div>

            <div class="overview-item red">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p>New Cases Today</p>
                <strong>{{ $todayCases ?? 0 }}</strong>
            </div>
        </div>
    </div>
</div>
@endsection
