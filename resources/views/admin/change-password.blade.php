@extends('admin.layout')

@section('title', 'Change Password')

@section('content')
<style>
    /* Card Container */
    .password-card {
        max-width: 650px;
        margin: 2.5rem auto;
        background: #fff;
        border-radius: 14px;
        padding: 2rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    /* Header */
    .password-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .password-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .password-icon {
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
    .password-header h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
    }
    .password-header small {
        color: #6b7280;
        font-size: 0.9rem;
    }

    /* Form Styling */
    .form-group {
        margin-bottom: 1.2rem;
        display: flex;
        flex-direction: column;
    }
    .form-group label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.35rem;
        font-size: 0.9rem;
    }
    .form-group input {
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        background: #f9fafb;
        transition: all 0.2s ease;
    }
    .form-group input:focus {
        border-color: #0ea5a3;
        background: #fff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(14, 165, 163, 0.15);
    }

    /* Alert */
    .alert {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    .alert-success {
        background-color: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }

    /* Buttons */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.8rem;
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

    @media (max-width: 640px) {
        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<div class="password-card">
    <div class="password-header">
        <div class="password-title">
            <div class="password-icon"><i class="bi bi-lock"></i></div>
            <div>
                <h2>Change Password</h2>
                <small>Secure your account by updating your password</small>
            </div>
        </div>
        <a href="{{ route('admin.profile') }}" class="btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profile.password.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" placeholder="Enter your current password">
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="password" placeholder="Enter new password">
        </div>

        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" placeholder="Re-enter new password">
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.profile') }}" class="btn-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
            <button class="btn-primary" type="submit">
                <i class="bi bi-save"></i> Update Password
            </button>
        </div>
    </form>
</div>
@endsection
