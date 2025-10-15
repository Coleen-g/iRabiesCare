@extends('admin.layout')

@section('title','Create Case')

@section('content')
    <style>
        .form-grid { display:grid; grid-template-columns: 1fr 1fr; gap:1rem; }
        .form-group { margin-bottom: .75rem; }
        .form-label { display:block; font-weight:600; margin-bottom:.25rem; color:#374151 }
        .form-input, .form-select, .form-textarea { width:100%; padding:.5rem; border:1px solid #e5e7eb; border-radius:6px; background:#fff }
        .form-textarea { min-height:120px; resize:vertical }
        .form-actions { display:flex; gap:.75rem; align-items:center; margin-top:.75rem }
        .btn-primary { background:#2563eb; color:#fff; padding:.5rem 1rem; border-radius:6px; border:0 }
        .btn-secondary { background:transparent; color:#374151; padding:.5rem 1rem; border-radius:6px; border:1px solid transparent }
    </style>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
        <h2 style="margin:0">Create Case</h2>
        <a href="{{ route('admin.cases.index') }}" class="btn-secondary">Back to cases</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.cases.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Patient</label>
                    <select name="patient_id" required class="form-select">
                        <option value="">-- select patient --</option>
                        @foreach($patients as $pt)
                            <option value="{{ $pt->id }}">{{ $pt->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Date Reported</label>
                    <input name="date_reported" type="date" class="form-input" />
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="open">Open</option>
                        <option value="in-progress">In Progress</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Reported By (optional)</label>
                    <input name="reported_by" class="form-input" placeholder="Name or contact" />
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Create Case</button>
                <a href="{{ route('admin.cases.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
