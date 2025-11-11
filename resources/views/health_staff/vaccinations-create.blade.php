@extends('health_staff.layout')

@section('title', 'Record Vaccination')

@section('content')
{{-- Ensure Bootstrap Icons are available --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* ===== Unified Health Staff Form Styling ===== */
    .edit-card {
        max-width: 1150px;
        margin: 2.5rem auto;
        background: #fff;
        border-radius: 14px;
        padding: 2rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .edit-title {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .edit-icon {
        width: 3rem;
        height: 3rem;
        background: #000;
        color: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .edit-header h2 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 600;
    }

    .edit-header small {
        color: #6b7280;
        font-size: 0.9rem;
    }

    /* ===== Form Grid ===== */
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group label i {
        color: #000;
        font-size: 1rem;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        padding: 0.7rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        background: #f9fafb;
        transition: all 0.2s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: #000;
        background: #fff;
        outline: none;
        box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.08);
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    /* ===== Buttons ===== */
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    .btn-primary {
        background: #000;
        color: #fff;
        padding: 0.7rem 1.3rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.25s ease;
    }

    .btn-primary:hover {
        background: #111827;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #111;
        padding: 0.7rem 1.3rem;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.25s ease;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    @media (max-width: 800px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="edit-card">
    <div class="edit-header">
        <div class="edit-title">
            <div class="edit-icon"><i class="bi bi-syringe"></i></div>
            <div>
                <h2>Record Vaccination</h2>
                <small>Enter vaccination details for a patient</small>
            </div>
        </div>
        <a href="{{ route('health_staff.vaccinations.index') }}" class="btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Vaccinations
        </a>
    </div>

    <form method="POST" action="{{ route('health_staff.vaccinations.store') }}">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label><i class="bi bi-person"></i> Patient</label>
                <select name="patient_id" required>
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $pt)
                        <option value="{{ $pt->id }}" {{ old('patient_id') == $pt->id ? 'selected' : '' }}>
                            {{ $pt->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label><i class="bi bi-calendar3"></i> Date Given</label>
                <input name="date_given" type="date" value="{{ old('date_given') }}" />
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label><i class="bi bi-prescription2"></i> Vaccine</label>
                <input name="vaccine" value="{{ old('vaccine') }}" placeholder="e.g. Rabivax" />
            </div>

            <div class="form-group">
                <label><i class="bi bi-droplet"></i> Dose</label>
                <input name="dose" value="{{ old('dose') }}" placeholder="e.g. 0.5 mL" />
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group" style="grid-column: 1 / -1;">
                <label><i class="bi bi-person-badge"></i> Administered By</label>
                <input name="administered_by" value="{{ old('administered_by') }}" placeholder="Staff name or clinic" />
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label><i class="bi bi-journal-medical"></i> Remarks</label>
                <textarea name="remarks" rows="4" placeholder="Additional remarks...">{{ old('remarks') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="bi bi-file-medical"></i> Record Vaccination
            </button>
            <a href="{{ route('health_staff.vaccinations.index') }}" class="btn-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
