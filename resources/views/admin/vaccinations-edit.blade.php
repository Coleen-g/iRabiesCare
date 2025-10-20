@extends('admin.layout')

@section('title', 'Edit Vaccination')

@section('content')
<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafb;
    }

    .edit-container {
        max-width: 700px;
        margin: 2rem auto;
        background: #fff;
        padding: 2rem 2.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .edit-container h2 {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 1.6rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #2563eb;
        padding-bottom: 0.5rem;
    }

    .edit-container h2 i {
        color: #2563eb;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
    }

    label {
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    label i {
        color: #2563eb;
        font-size: 1rem;
    }

    input[type="text"],
    input[type="date"],
    select,
    textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    button {
        background-color: #2563eb;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button:hover {
        background-color: #1d4ed8;
        transform: translateY(-2px);
    }

    a.back-link {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        color: #374151;
        font-weight: 500;
        font-size: 0.95rem;
        transition: color 0.3s ease;
    }

    a.back-link:hover {
        color: #2563eb;
    }

    @media (max-width: 600px) {
        .edit-container {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }

        button, .back-link {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="edit-container">
    <h2><i class="fa-solid fa-syringe"></i> Edit Vaccination</h2>

    <form method="POST" action="{{ route('admin.vaccinations.update', $vaccination) }}">
        @csrf
        @method('PUT')

        <div>
            <label><i class="fa-solid fa-user"></i> Patient</label>
            <select name="patient_id" required class="searchable-patient-select">
                @foreach($patients as $pt)
                    <option value="{{ $pt->id }}" {{ $pt->id == $vaccination->patient_id ? 'selected' : '' }}>
                        {{ $pt->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label><i class="fa-solid fa-calendar-day"></i> Date Given</label>
            <input name="date_given" type="date" value="{{ $vaccination->date_given }}" />
        </div>

        <div>
            <label><i class="fa-solid fa-prescription-bottle-medical"></i> Vaccine</label>
            <input name="vaccine" value="{{ $vaccination->vaccine }}" placeholder="e.g. Rabivax" />
        </div>

        <div>
            <label><i class="fa-solid fa-vial"></i> Dose</label>
            <input name="dose" value="{{ $vaccination->dose }}" placeholder="e.g. 0.5 mL" />
        </div>

        <div>
            <label><i class="fa-solid fa-user-nurse"></i> Administered By</label>
            <input name="administered_by" value="{{ $vaccination->administered_by }}" placeholder="e.g. Dr. Smith" />
        </div>

        <div>
            <label><i class="fa-solid fa-notes-medical"></i> Notes</label>
            <textarea name="notes" placeholder="Additional remarks...">{{ $vaccination->notes }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Update Vaccination</button>
            <a href="{{ route('admin.vaccinations.index') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>
    </form>
</div>
@endsection
