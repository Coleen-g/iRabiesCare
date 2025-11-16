@extends('admin.layout')

@section('title', 'Profile')

@section('content')
<style>
    /* Card Container */
    .profile-card {
        max-width: 950px;
        margin: 2.5rem auto;
        background: #fff;
        border-radius: 14px;
        padding: 2rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    /* Header */
    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .profile-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .profile-icon {
        width: 3rem;
        height: 3rem;
        background: linear-gradient(135deg, #0ea5a3, #059669);
        color: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: 0 4px 10px rgba(2, 6, 23, 0.15);
    }
    .profile-header h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
    }
    .profile-header small {
        color: #6b7280;
        font-size: 0.9rem;
    }

    /* Grid Form */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem 2rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-group label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.35rem;
        font-size: 0.9rem;
    }
    .form-group input,
    .form-group select {
        padding: 0.7rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        background: #f9fafb;
        transition: all 0.2s ease;
    }
    .form-group input:focus,
    .form-group select:focus {
        border-color: #0ea5a3;
        background: #fff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(14, 165, 163, 0.15);
    }

    /* Profile Image */
    .profile-preview img {
        height: 70px;
        width: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
    }

    /* Buttons */
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }
    .btn-primary {
        background: #0ea5a3;
        color: #fff;
        padding: 0.7rem 1.3rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-weight: 600;
        transition: 0.3s ease;
    }
    .btn-primary:hover {
        background: #0f766e;
        transform: translateY(-2px);
    }
    .btn-secondary {
        background: #f3f4f6;
        color: #111;
        padding: 0.7rem 1.3rem;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-weight: 600;
        transition: 0.3s ease;
    }
    .btn-secondary:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    @media (max-width: 800px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; align-items: stretch; }
    }
</style>

<div class="profile-card">
    <div class="profile-header">
        <div class="profile-title">
            <div class="profile-icon"><i class="bi bi-person-circle"></i></div>
            <div>
                <h2>Admin Profile</h2>
                <small>Manage your personal information and account</small>
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}">
            </div>

            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="contact_number" value="{{ old('contact_number', auth()->user()->contact_number ?? '') }}">
            </div>

            
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.profile.password') }}" class="btn-secondary">
                <i class="bi bi-lock"></i> Change Password
            </a>
            <button class="btn-primary" type="submit">
                <i class="bi bi-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
