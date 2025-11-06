@extends('admin.layout')

@section('title', 'Edit Case')

@section('content')
<style>
    /* unified admin form styling (matches create/edit patient) */
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
        transition: background 0.2s;
    }

    .btn-primary:hover {
        background: #111827;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #111;
        padding: 0.7rem 1.3rem;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        font-weight: 500;
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
        }
    }
</style>

<div class="edit-card">
    <div class="edit-header">
        <div class="edit-title">
            <div class="edit-icon"><i class="bi bi-journal-medical"></i></div>
            <div>
                <h2>Edit Case</h2>
                <small>Modify case details</small>
            </div>
        </div>
        <a href="{{ route('admin.cases.index') }}" class="btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Cases
        </a>
    </div>

    <form method="POST" action="{{ route('admin.cases.update', $case) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label>Patient</label>
                <select name="patient_id" required>
                    @foreach($patients as $pt)
                        <option value="{{ $pt->id }}" {{ $pt->id == old('patient_id', $case->patient_id) ? 'selected' : '' }}>
                            {{ $pt->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Date Reported</label>
                <input name="date_reported" type="date" value="{{ old('date_reported', $case->date_reported) }}" required />
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="open" {{ old('status', $case->status) == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in-progress" {{ old('status', $case->status) == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="closed" {{ old('status', $case->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div class="form-group">
                <label>Reported By (Optional)</label>
                <input name="reported_by" value="{{ old('reported_by', $case->reported_by) }}" placeholder="Name or contact" />
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Date of Exposure</label>
                <input name="exposure_date" type="date" value="{{ old('exposure_date', $case->exposure_date) }}" />
            </div>

            <div class="form-group">
                <label>Type of Exposure</label>
                <input name="exposure_type" value="{{ old('exposure_type', $case->exposure_type) }}" placeholder="e.g. Bite, Scratch" />
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Location of Wounds</label>
                <input name="wounds_location" value="{{ old('wounds_location', $case->wounds_location) }}" placeholder="e.g. Left arm" />
            </div>

            <div class="form-group">
                <label>Category</label>
                <input name="category" value="{{ old('category', $case->category) }}" placeholder="e.g. Category II" />
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Species of Animal</label>
                <input name="animal_species" value="{{ old('animal_species', $case->animal_species) }}" placeholder="Dog, Cat, etc." />
            </div>

            <div class="form-group">
                <label>Animal Status</label>
                <input name="animal_status" value="{{ old('animal_status', $case->animal_status) }}" placeholder="e.g. Alive, Dead, Unknown" />
            </div>
        </div>

        <div class="form-group" style="grid-column:1/3;">
            <label>Description</label>
            <textarea name="description" placeholder="Enter case details...">{{ old('description', $case->description) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="bi bi-pencil-square"></i> Update Case
            </button>
            <a href="{{ route('admin.cases.index') }}" class="btn-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
