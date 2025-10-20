@extends('admin.layout')

@section('title','Create Case')

@section('content')
<!-- Import Lucide icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    /* ===== Page Header ===== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .page-header h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ===== Card Container ===== */
    .card {
        background: #ffffff;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .card:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    /* ===== Form Grid ===== */
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
        gap: 0.4rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.35rem;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.65rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background-color: #f9fafb;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #2563eb;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }

    /* ===== Buttons ===== */
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-primary {
        background: linear-gradient(90deg, #2563eb, #1d4ed8);
        color: #fff;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 3px 8px rgba(37, 99, 235, 0.25);
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #1e40af, #1d4ed8);
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-secondary {
        background: transparent;
        color: #374151;
        padding: 0.7rem 1.3rem;
        font-weight: 600;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-secondary:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }

    /* ===== Select Arrow ===== */
    select {
        appearance: none;
        background-image: url('data:image/svg+xml;utf8,<svg fill="none" stroke="%236b7280" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>');
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1em;
    }
</style>

<div class="page-header">
    <h2><i data-lucide="folder-plus"></i> Create Case</h2>
    <a href="{{ route('admin.cases.index') }}" class="btn-secondary">
        <i data-lucide="arrow-left"></i> Back to Cases
    </a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.cases.store') }}">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label"><i data-lucide="user"></i> Patient</label>
                <select name="patient_id" required class="form-select searchable-patient-select">
                    <option value="">-- select patient --</option>
                    @foreach($patients as $pt)
                        <option value="{{ $pt->id }}">{{ $pt->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label"><i data-lucide="calendar"></i> Date Reported</label>
                <input name="date_reported" type="date" class="form-input" />
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label"><i data-lucide="flag"></i> Status</label>
                <select name="status" class="form-select">
                    <option value="open">Open</option>
                    <option value="in-progress">In Progress</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label"><i data-lucide="user-check"></i> Reported By (optional)</label>
                <input name="reported_by" class="form-input" placeholder="Name or contact" />
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><i data-lucide="file-text"></i> Description</label>
            <textarea name="description" class="form-textarea" placeholder="Enter case details..."></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i data-lucide="save"></i> Create Case
            </button>
            <a href="{{ route('admin.cases.index') }}" class="btn-secondary">
                <i data-lucide="x-circle"></i> Cancel
            </a>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
