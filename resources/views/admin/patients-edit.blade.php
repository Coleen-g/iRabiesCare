@extends('admin.layout')

@section('title', 'Edit Patient')

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
    textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus,
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
    <h2><i class="fa-solid fa-user-pen"></i> Edit Patient</h2>

    <form method="POST" action="{{ route('admin.patients.update', $patient) }}">
        @csrf
        @method('PUT')

        <div>
            <label><i class="fa-solid fa-id-badge"></i> Name</label>
            <input name="name" value="{{ old('name', $patient->name) }}" required placeholder="Enter patient's full name" />
        </div>

        <div>
            <label><i class="fa-solid fa-calendar-day"></i> Date of Birth</label>
            <input name="dob" type="date" value="{{ old('dob', $patient->dob) }}" />
        </div>

        <div>
            <label><i class="fa-solid fa-venus-mars"></i> Gender</label>
            <input name="gender" value="{{ old('gender', $patient->gender) }}" placeholder="e.g., Male, Female, Other" />
        </div>

        <div>
            <label><i class="fa-solid fa-phone"></i> Contact</label>
            <input name="contact" value="{{ old('contact', $patient->contact) }}" placeholder="Enter contact number" />
        </div>

        <div>
            <label><i class="fa-solid fa-location-dot"></i> Address</label>
            <textarea name="address" placeholder="Enter patient's full address">{{ old('address', $patient->address) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Update Patient</button>
            <a href="{{ route('admin.patients.index') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div>
    </form>
</div>
@endsection
