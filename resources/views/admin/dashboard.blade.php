@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<style>
    /* === Base Styles === */
    .dashboard-container {
        padding: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    /* === Dashboard Header === */
    .dashboard-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-title h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .header-icon {
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #000000, #2c2c2c);
        border-radius: 12px;
        color: white;
        font-size: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* === Stats Grid === */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.15);
        border-color: #d1d5db;
    }

    .stat-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        position: relative;
        z-index: 1;
    }

    .stat-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1);
    }

    .stat-content {
        position: relative;
        z-index: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
        line-height: 1;
        margin-bottom: 1rem;
    }

    .stat-trend {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* === Card Variants === */
    .stat-icon.primary {
        background: linear-gradient(135deg, #000000, #2c2c2c);
        color: white;
    }

    .stat-icon.info {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .stat-icon.success {
        background: linear-gradient(135deg, #10b981, #047857);
        color: white;
    }

    .stat-trend.primary {
        background-color: #f3f4f6;
        color: #111827;
    }

    .stat-trend.info {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .stat-trend.success {
        background-color: #d1fae5;
        color: #065f46;
    }

    /* === Overview Section === */
    .overview-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .overview-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .overview-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .overview-header .icon {
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #000000, #2c2c2c);
        border-radius: 10px;
        color: white;
        font-size: 1.125rem;
    }

    .activity-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .activity-grid {
            grid-template-columns: 1fr;
        }
    }

    .activity-item {
        padding: 1.25rem;
        background: #f9fafb;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: #f3f4f6;
        transform: translateY(-2px);
    }

    .activity-icon {
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.25rem;
    }

    .activity-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .activity-label {
        font-size: 0.875rem;
        color: #6b7280;
    }

    /* === Recent Patients Section === */
    .recent-patients {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .view-all {
        color: #2563eb;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-all:hover {
        color: #1d4ed8;
    }

    .table-container {
        overflow-x: auto;
    }

    .patient-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .patient-name {
        font-weight: 500;
        color: #111827;
    }

    .patient-type {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.pending { background: #fef3c7; color: #92400e; }
    .status-badge.completed { background: #dbeafe; color: #1e40af; }

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-action.view {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-action.edit {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        opacity: 0.9;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 0;
    }

    .empty-state-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
    }

    .empty-state-content i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .empty-state-content p {
        margin: 0;
        font-size: 0.875rem;
    }
</style>

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="header-title">
            <div class="header-icon">
                <i class="bi bi-speedometer2"></i>
            </div>
            <h1>Admin Dashboard</h1>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <a href="{{ route('admin.patients.index') }}" style="text-decoration:none;color:inherit;">
            <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Patients</div>
                <div class="stat-value">{{ $patientsCount ?? 0 }}</div>
                <div class="stat-trend primary">
                    <i class="bi bi-person-plus"></i>
                    <span>Active Patients</span>
                </div>
            </div>
            </div>
        </a>

        <a href="{{ route('admin.cases.index') }}" style="text-decoration:none;color:inherit;">
            <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon info">
                    <i class="bi bi-journal-medical"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-label">Reported Cases</div>
                <div class="stat-value">{{ $casesCount ?? 0 }}</div>
                <div class="stat-trend info">
                    <i class="bi bi-clipboard-data"></i>
                    <span>Total Reports</span>
                </div>
            </div>
            </div>
        </a>

        <a href="{{ route('admin.vaccinations.index') }}" style="text-decoration:none;color:inherit;">
            <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon success">
                    <i class="bi bi-capsule"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-label">Vaccinations Given</div>
                <div class="stat-value">{{ $vaccinationsCount ?? 0 }}</div>
                <div class="stat-trend success">
                    <i class="bi bi-check-circle"></i>
                    <span>Completed</span>
                </div>
            </div>
            </div>
        </a>
    </div>

    <!-- Overview Section -->
    <div class="overview-section">
        <div class="overview-header">
            <div class="icon">
                <i class="bi bi-graph-up"></i>
            </div>
            <h2>Today's Activity</h2>
        </div>

        <div class="activity-grid">
            <div class="activity-item">
                <div class="activity-icon" style="color: #2563eb;">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="activity-value">{{ $todayRegistrations ?? 0 }}</div>
                <div class="activity-label">New Registrations</div>
            </div>

            <div class="activity-item">
                <div class="activity-icon" style="color: #16a34a;">
                    <i class="bi bi-capsule"></i>
                </div>
                <div class="activity-value">{{ $todayVaccinations ?? 0 }}</div>
                <div class="activity-label">Vaccinations</div>
            </div>

            <div class="activity-item">
                <div class="activity-icon" style="color: #dc2626;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="activity-value">{{ $todayCases ?? 0 }}</div>
                <div class="activity-label">New Cases</div>
            </div>
        </div>
    </div>

    <!-- Recent Patients Section -->
    <div class="recent-patients">
        <div class="section-header">
            <h2>Recent Patients</h2>
            <a href="{{ route('admin.patients.index') }}" class="view-all">
                View All Patients
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Owner</th>
                        <th>Contact</th>
                        <th>Last Visit</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPatients ?? [] as $patient)
                    <tr>
                        <td>
                            <div class="patient-info">
                                <span class="patient-name">{{ $patient->name }}</span>
                                <span class="patient-type">{{ $patient->type }}</span>
                            </div>
                        </td>
                        <td>{{ $patient->owner_name }}</td>
                        <td>{{ $patient->contact_number }}</td>
                        <td>{{ $patient->last_visit ? $patient->last_visit->format('M d, Y') : 'No visits yet' }}</td>
                        <td>
                            <span class="status-badge {{ strtolower($patient->status) }}">
                                {{ $patient->status }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn-action view" title="View Patient">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn-action edit" title="Edit Patient">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-state-content">
                                <i class="bi bi-inbox"></i>
                                <p>No recent patients</p>
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