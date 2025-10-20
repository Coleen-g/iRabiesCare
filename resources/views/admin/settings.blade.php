@extends('admin.layout')

@section('title','Settings')

@section('content')
<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafb;
    }

    .settings-container {
        max-width: 900px;
        margin: 2rem auto;
        background: #fff;
        border-radius: 12px;
        padding: 2rem 2.5rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .settings-header {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        border-bottom: 2px solid #2563eb;
        padding-bottom: 0.6rem;
        margin-bottom: 1.5rem;
    }

    .settings-header h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .settings-header i {
        color: #2563eb;
        font-size: 1.4rem;
    }

    .settings-section {
        margin-bottom: 1.5rem;
    }

    .settings-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2563eb;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.8rem;
    }

    .settings-card {
        background-color: #f3f4f6;
        border-radius: 10px;
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .settings-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .settings-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.6rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .settings-item:last-child {
        border-bottom: none;
    }

    .settings-item span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        color: #374151;
    }

    .settings-item i {
        color: #2563eb;
    }

    .settings-item button {
        background-color: #2563eb;
        color: #fff;
        border: none;
        padding: 0.4rem 0.9rem;
        border-radius: 6px;
        font-size: 0.85rem;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .settings-item button:hover {
        background-color: #1d4ed8;
        transform: scale(1.05);
    }

    @media (max-width: 700px) {
        .settings-container {
            padding: 1.5rem;
        }

        .settings-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.4rem;
        }

        .settings-item button {
            align-self: flex-end;
        }
    }
</style>

<div class="settings-container">
    <div class="settings-header">
        <i class="fa-solid fa-gear"></i>
        <h2>System Settings</h2>
    </div>

    {{-- System Preferences --}}
    <div class="settings-section">
        <div class="settings-title"><i class="fa-solid fa-cogs"></i> System Preferences</div>
        <div class="settings-card">
            <div class="settings-item">
                <span><i class="fa-solid fa-clock"></i> Date & Time Configuration</span>
                <button>Edit</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-database"></i> Backup & Restore Database</span>
                <button>Manage</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-syringe"></i> Vaccine Data Management</span>
                <button>Open</button>
            </div>
        </div>
    </div>

    {{-- Notification Settings --}}
    <div class="settings-section">
        <div class="settings-title"><i class="fa-solid fa-bell"></i> Notifications</div>
        <div class="settings-card">
            <div class="settings-item">
                <span><i class="fa-solid fa-envelope"></i> Email Notifications</span>
                <button>Configure</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-mobile-screen"></i> SMS Alerts</span>
                <button>Configure</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-user-shield"></i> Admin Alerts</span>
                <button>View</button>
            </div>
        </div>
    </div>

    {{-- Account Settings --}}
    <div class="settings-section">
        <div class="settings-title"><i class="fa-solid fa-user-cog"></i> Account Settings</div>
        <div class="settings-card">
            <div class="settings-item">
                <span><i class="fa-solid fa-user-pen"></i> Edit Profile</span>
                <button>Edit</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-key"></i> Change Password</span>
                <button>Change</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-right-from-bracket"></i> Logout All Devices</span>
                <button>Logout</button>
            </div>
        </div>
    </div>
</div>
@endsection
