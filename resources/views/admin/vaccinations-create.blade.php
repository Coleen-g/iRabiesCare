@extends('admin.layout')

@section('title', 'Record Vaccination')

@section('content')
<style>
    /* ===== Page Header ===== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-header h2 i {
        color: #2563eb;
    }

    .btn-secondary {
        background: #fff;
        color: #374151;
        padding: 0.6rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-secondary:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }

    /* ===== Card ===== */
    .card {
        background: #ffffff;
        border-radius: 10px;
        padding: 1.75rem 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    /* ===== Form Layout ===== */
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
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.35rem;
    }

    /* Input container with icon */
    .input-icon {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.55rem 0.75rem;
        transition: all 0.2s ease;
    }

    .input-icon i {
        color: #6b7280;
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    .input-icon input,
    .input-icon select,
    .input-icon textarea {
        border: none;
        outline: none;
        flex: 1;
        font-size: 0.95rem;
        background: transparent;
    }

    .input-icon:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    textarea.form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .error-message {
        color: #b91c1c;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* ===== Buttons ===== */
    .form-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        margin-top: 1.25rem;
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary i {
        font-size: 1rem;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
    }
</style>

<div class="page-header">
    <h2><i class="fa-solid fa-syringe"></i> Record Vaccination</h2>
    <a href="{{ route('admin.vaccinations.index') }}" class="btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back to Vaccinations
    </a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.vaccinations.store') }}">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Patient</label>
                <div class="input-icon">
                    <i class="fa-solid fa-user"></i>
                    <select name="patient_id" required class="form-select searchable-patient-select">
                        <option value="">-- Select patient --</option>
                        @foreach($patients as $pt)
                            <option value="{{ $pt->id }}" {{ old('patient_id') == $pt->id ? 'selected' : '' }}>
                                {{ $pt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('patient_id') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Date Given</label>
                <div class="input-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                    <input name="date_given" type="date" value="{{ old('date_given') }}" class="form-input" />
                </div>
                @error('date_given') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Vaccine</label>
                <div class="input-icon">
                    <i class="fa-solid fa-syringe"></i>
                    <input name="vaccine" value="{{ old('vaccine') }}" placeholder="e.g. Rabivax" class="form-input" />
                </div>
                @error('vaccine') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Dose</label>
                <div class="input-icon">
                    <i class="fa-solid fa-droplet"></i>
                    <input name="dose" value="{{ old('dose') }}" placeholder="e.g. 0.5 mL" class="form-input" />
                </div>
                @error('dose') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label class="form-label">Administered By</label>
                <div class="input-icon">
                    <i class="fa-solid fa-user-nurse"></i>
                    <input name="administered_by" value="{{ old('administered_by') }}" placeholder="Staff name or clinic" class="form-input" />
                </div>
                @error('administered_by') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label class="form-label">Notes</label>
                <div class="input-icon">
                    <i class="fa-solid fa-notes-medical"></i>
                    <textarea name="notes" rows="4" class="form-textarea" placeholder="Additional remarks...">{{ old('notes') }}</textarea>
                </div>
                @error('notes') <div class="error-message">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-file-medical"></i> Record Vaccination
            </button>
            <a href="{{ route('admin.vaccinations.index') }}" class="btn-cancel">
                <i class="fa-solid fa-xmark"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
