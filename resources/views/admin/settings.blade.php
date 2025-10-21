@extends('admin.layout')

@section('title','Settings')

@section('content')
<style>
    body { background:#f5f6f7; font-family: "Inter", system-ui, sans-serif }
    .edit-card { max-width: 1150px; margin: 2.5rem auto; background: #fff; border-radius: 14px; padding: 2rem; border:1px solid #e5e7eb; box-shadow:0 8px 20px rgba(0,0,0,0.05); }
    .page-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1rem }
    .page-title { display:flex; align-items:center; gap:.75rem }
    .page-title i { background:#000;color:#fff;padding:0.45rem;border-radius:8px }
    h2.page { margin:0; font-size:1.4rem; font-weight:700 }

    .settings-section { margin-bottom:1.25rem }
    .settings-title { font-size:1rem; font-weight:600; color:#065f46; margin-bottom:0.6rem; display:flex; gap:0.5rem; align-items:center }

    .settings-card { background:#f9fafb; border-radius:12px; padding:1rem; border:1px solid #e8f5e9 }
    .settings-item { display:flex; align-items:center; justify-content:space-between; padding:0.6rem 0; border-bottom:1px solid #e5e7eb }
    .settings-item:last-child{ border-bottom:none }
    .settings-item span{ display:flex; align-items:center; gap:0.5rem }

    .btn-primary { background:#000;color:#fff;padding:0.45rem 0.9rem;border-radius:8px;border:none }
    .btn-secondary { background:#f3f4f6;color:#111;padding:0.45rem 0.9rem;border-radius:8px;border:none }

    @media(max-width:700px){ .edit-card{padding:1.5rem} .settings-item{flex-direction:column;align-items:flex-start} .settings-item button{align-self:flex-end} }
</style>

<div class="edit-card">
    <div class="page-header">
        <div class="page-title">
            <i class="fa-solid fa-gear"></i>
            <h2 class="page">System Settings</h2>
        </div>
        <div>
            <a href="#" class="btn-secondary">Save Changes</a>
        </div>
    </div>

    {{-- System Preferences --}}
    <div class="settings-section">
        <div class="settings-title"><i class="fa-solid fa-cogs"></i> System Preferences</div>
        <div class="settings-card">
            <div class="settings-item">
                <span><i class="fa-solid fa-clock"></i> Date & Time Configuration</span>
                <button class="btn-primary">Edit</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-database"></i> Backup & Restore Database</span>
                <button class="btn-primary">Manage</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-syringe"></i> Vaccine Data Management</span>
                <button class="btn-primary">Open</button>
            </div>
        </div>
    </div>

    {{-- Notification Settings --}}
    <div class="settings-section">
        <div class="settings-title"><i class="fa-solid fa-bell"></i> Notifications</div>
        <div class="settings-card">
            <div class="settings-item">
                <span><i class="fa-solid fa-envelope"></i> Email Notifications</span>
                <button class="btn-primary">Configure</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-mobile-screen"></i> SMS Alerts</span>
                <button class="btn-primary">Configure</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-user-shield"></i> Admin Alerts</span>
                <button class="btn-primary">View</button>
            </div>
        </div>
    </div>

    {{-- Account Settings --}}
    <div class="settings-section">
        <div class="settings-title"><i class="fa-solid fa-user-cog"></i> Account Settings</div>
        <div class="settings-card">
            <div class="settings-item">
                <span><i class="fa-solid fa-user-pen"></i> Edit Profile</span>
                <button class="btn-primary">Edit</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-key"></i> Change Password</span>
                <button class="btn-primary">Change</button>
            </div>
            <div class="settings-item">
                <span><i class="fa-solid fa-right-from-bracket"></i> Logout All Devices</span>
                <button class="btn-primary">Logout</button>
            </div>
        </div>
    </div>
</div>
@endsection
