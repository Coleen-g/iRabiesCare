@extends('admin.layout')

@section('title','Create Patient')

@section('content')
<!-- ✅ Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /* ===== Page Header ===== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }
    .page-header h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .page-header i {
        color: #2563eb;
        font-size: 1.4rem;
    }

    /* ===== Card ===== */
    .card {
        background: #ffffff;
        border-radius: 10px;
        padding: 1.5rem 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        max-width: 800px;
    }
    .card:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    /* ===== Form ===== */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.35rem;
        font-size: 0.95rem;
        gap: 0.4rem;
    }
    .form-label i {
        color: #2563eb;
        font-size: 0.9rem;
    }

    .form-input,
    .form-textarea {
        width: 100%;
        padding: 0.6rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background-color: #fff;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    /* ===== Buttons ===== */
    .form-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        margin-top: 1rem;
    }

    .btn-primary {
        background: linear-gradient(90deg, #2563eb, #1d4ed8);
        color: #fff;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
    }

    .btn-secondary {
        background: transparent;
        color: #374151;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .btn-secondary:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }
</style>

<div class="page-header">
    <h2><i class="fa-solid fa-user-plus"></i> Create Patient</h2>
    <a href="{{ route('admin.patients.index') }}" class="btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back to Patients
    </a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.patients.store') }}">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-user"></i> Full Name</label>
                <input name="name" required class="form-input" placeholder="Enter patient's full name" />
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-calendar-days"></i> Date of Birth</label>
                <input name="dob" type="date" class="form-input" />
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-venus-mars"></i> Gender</label>
                <input name="gender" class="form-input" placeholder="e.g. Male, Female, Other" />
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-phone"></i> Contact Number</label>
                <input name="contact" class="form-input" placeholder="e.g. 09123456789" />
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><i class="fa-solid fa-location-dot"></i> Address</label>
            <textarea name="address" class="form-textarea" placeholder="Enter complete address"></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Create Patient
            </button>
            <a href="{{ route('admin.patients.index') }}" class="btn-secondary">
                <i class="fa-solid fa-xmark"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
