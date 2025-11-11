@extends('admin.layout')

@section('title', 'Record Vaccination')

@section('content')
<style>
    /* ===== Unified Admin Form Styling ===== */
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
        align-items: center;
        gap: 1rem;
    }

    .edit-icon {
        width: 3rem;
        height: 3rem;
        background: #2563eb;
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
        color: #111827;
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
    }

    /* Inputs, Selects, Textareas */
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.7rem 0.9rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        background: #f9fafb;
        transition: all 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #2563eb;
        background: #fff;
        outline: none;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    .error-message {
        color: #b91c1c;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    /* ===== Form Actions ===== */
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.75rem;
    }

    .btn-primary {
        background: #2563eb;
        color: #fff;
        padding: 0.75rem 1.3rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #111;
        padding: 0.75rem 1.3rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: background 0.2s;
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
            align-items: stretch;
        }
    }
</style>

<div class="edit-card">
    <div class="edit-header">
        <div class="edit-title">
            <div class="edit-icon"><i class="bi bi-syringe"></i></div>
            <div>
                <h2>Record Vaccination</h2>
                <small>Register a new vaccination entry</small>
            </div>
        </div>
        <a href="{{ route('admin.vaccinations.index') }}" class="btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Vaccinations
        </a>
    </div>

    <form method="POST" action="{{ route('admin.vaccinations.store') }}">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label>Patient</label>
                <select name="patient_id" required class="searchable-patient-select">
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $pt)
                        <option value="{{ $pt->id }}" {{ old('patient_id') == $pt->id ? 'selected' : '' }}>
                            {{ $pt->name }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Date Given</label>
                <input name="date_given" type="date" value="{{ old('date_given') }}" />
                @error('date_given') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Vaccine</label>
                <input name="vaccine" placeholder="e.g. Rabivax" value="{{ old('vaccine') }}" />
                @error('vaccine') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Dose</label>
                <input name="dose" placeholder="e.g. 0.5 mL" value="{{ old('dose') }}" />
                @error('dose') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Administered By</label>
                <input name="administered_by" placeholder="Staff name or clinic" value="{{ old('administered_by') }}" />
                @error('administered_by') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Remarks</label>
                <textarea name="remarks" rows="4" placeholder="Additional remarks...">{{ old('remarks') }}</textarea>
                @error('remarks') <div class="error-message">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="bi bi-file-medical"></i> Record Vaccination
            </button>
            <a href="{{ route('admin.vaccinations.index') }}" class="btn-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
