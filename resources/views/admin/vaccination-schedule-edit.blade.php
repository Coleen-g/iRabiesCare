@extends('admin.layout')

@section('title', 'Edit Vaccination Schedule')

@section('content')
<!-- ✅ Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container mt-4">
    <style>
        /* === Overall Page Styling === */
        body {
            background-color: #f8fafc;
            font-family: 'Poppins', sans-serif;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .header-icon {
            min-width: 56px;
            min-height: 56px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0ea5a3, #0284c7);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            box-shadow: 0 6px 14px rgba(14, 165, 163, 0.2);
        }

        .header-text small {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .header-text h2 {
            margin: 0.25rem 0 0 0;
            font-weight: 600;
            color: #111827;
        }

        .header-text p {
            color: #6b7280;
            font-size: 0.95rem;
            margin: 0;
        }

        /* === Card for Each Schedule === */
        .schedule-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.25rem;
            transition: all 0.2s ease;
        }

        .schedule-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.1);
        }

        .schedule-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .schedule-header i {
            font-size: 1.2rem;
            color: #059669;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
        }

        .hs-status-badge {
            padding: 8px 14px;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin-top: .5rem;
        }

        .hs-completed { background: linear-gradient(135deg, #10b981, #0ea5a3); }
        .hs-missed { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .hs-pending { background: linear-gradient(135deg, #f59e0b, #d97706); }

        .hs-small-btn {
            padding: 0.45rem 0.75rem;
            border-radius: 8px;
            border: none;
            color: #fff;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }

        .hs-small-btn i {
            font-size: 1rem;
        }

        .hs-done { background: #0ea5a3; }
        .hs-done:hover { background: #0d9488; }

        .hs-miss { background: #ef4444; }
        .hs-miss:hover { background: #dc2626; }

        .hs-remarks {
            width: 100%;
            min-height: 80px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.6rem;
            font-size: 0.95rem;
            resize: vertical;
            margin-top: 0.3rem;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.75rem;
        }

        .hs-save {
            background: #2563eb;
            color: #fff;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: background .2s ease;
        }

        .hs-save:hover {
            background: #1d4ed8;
        }

        .btn-outline-secondary i, .btn-secondary i {
            margin-right: 0.35rem;
        }

        @media (max-width: 720px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <!-- === HEADER === -->
    <div class="page-header">
        <div class="d-flex align-items-start gap-3">
            <div class="header-icon"><i class="bi bi-capsule-pill"></i></div>
            <div class="header-text">
                <small><i class="bi bi-speedometer2"></i> Dashboard / Vaccination Schedules</small>
                <h2>Edit Vaccination Schedule — {{ $user->name }}</h2>
                <p>Update vaccination dates, mark doses, and add remarks for this patient.</p>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.patients.show', optional($user->patient)->id ?? '#') }}" class="btn btn-outline-primary">
                <i class="bi bi-person-lines-fill"></i> View Patient
            </a>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Back to Patients
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- === FORM === -->
    <form method="POST" action="{{ route('admin.vaccination-schedule.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div id="schedule-form">
            @for($i = 1; $i <= 3; $i++)
                @php
                    $key = "schedule_{$i}";
                    $s = strtolower($schedule->{$key.'_status'} ?? 'pending');
                @endphp

                <div class="schedule-card" data-key="{{ $key }}">
                    <div class="schedule-header">
                        <i class="bi bi-calendar-week"></i>
                        <h5 class="mb-0 fw-semibold">Next Vaccination #{{ $i }}</h5>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $schedule->$key ?? '' }}">
                        <input type="hidden" name="{{ $key }}_status" id="{{ $key }}_status" value="{{ $s }}">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Remarks</label>
                        <textarea name="{{ $key }}_remarks" id="{{ $key }}_remarks" class="hs-remarks">{{ $schedule->{$key.'_remarks'} ?? '' }}</textarea>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-2 flex-wrap">
                        <div id="badge_{{ $key }}" class="hs-status-badge {{ $s === 'completed' ? 'hs-completed' : ($s === 'missed' ? 'hs-missed' : 'hs-pending') }}">
                            <i class="bi {{ $s === 'completed' ? 'bi-check-circle' : ($s === 'missed' ? 'bi-x-circle' : 'bi-hourglass-split') }}"></i>
                            {{ ucfirst($s) }}
                        </div>
                        <div class="d-flex gap-2 mt-2 mt-md-0">
                            <button type="button" onclick="markSchedule('{{ $key }}','missed')" class="hs-small-btn hs-miss">
                                <i class="bi bi-x-circle"></i> Mark Missed
                            </button>
                            <button type="button" onclick="markSchedule('{{ $key }}','completed')" class="hs-small-btn hs-done">
                                <i class="bi bi-check-circle"></i> Mark Done
                            </button>
                        </div>
                    </div>
                </div>
            @endfor

            <div class="mt-4">
                <label class="form-label fw-semibold" for="overall_remarks">
                    <i class="bi bi-flag"></i> Overall Schedule Status
                </label>
                <select id="overall_remarks" name="overall_remarks" class="form-select mt-1" style="max-width: 300px;">
                    <option value="" {{ $schedule->overall_remarks === '' ? 'selected' : '' }}>None</option>
                    <option value="Incomplete" {{ $schedule->overall_remarks === 'Incomplete' ? 'selected' : '' }}>Incomplete</option>
                    <option value="Completed" {{ $schedule->overall_remarks === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Missed All Schedule" {{ $schedule->overall_remarks === 'Missed All Schedule' ? 'selected' : '' }}>Missed All Schedule</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="hs-save">
                    <i class="bi bi-save2-fill"></i> Save Schedule
                </button>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<script>
const adminName = @json(optional(auth()->user())->name ?? 'Admin');
function markSchedule(key, status) {
    const statusInput = document.getElementById(key + '_status');
    if (!statusInput) return;
    statusInput.value = status;

    const ta = document.getElementById(key + '_remarks');
    const now = new Date();
    const timestamp = now.toLocaleString();
    const note = `Marked as ${status} by ${adminName} on ${timestamp}`;
    if (ta) ta.value = note;

    const badge = document.getElementById('badge_' + key);
    if (badge) {
        badge.classList.remove('hs-completed', 'hs-missed', 'hs-pending');
        let icon = 'bi-hourglass-split';
        if (status === 'completed') { badge.classList.add('hs-completed'); icon = 'bi-check-circle'; }
        else if (status === 'missed') { badge.classList.add('hs-missed'); icon = 'bi-x-circle'; }
        badge.innerHTML = `<i class="bi ${icon}"></i> ${status.charAt(0).toUpperCase() + status.slice(1)}`;
    }
}
</script>
@endsection
