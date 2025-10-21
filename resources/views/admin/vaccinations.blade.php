@extends('admin.layout')

@section('title','Vaccinations')

@section('content')
<style>
    /* === Unified Black & White Dashboard Theme === */
    body {
        font-family: "Poppins", sans-serif;
        background-color: #f9fafb;
        color: #111827;
        margin: 0;
        padding: 0;
    }

    .list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .list-header h2 {
        font-size: 1.4rem;
        font-weight: 600;
        color: #111;
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .list-header h2 i {
        background: linear-gradient(135deg, #000, #2c2c2c);
        color: #fff;
        padding: .6rem;
        border-radius: 10px;
        font-size: 1rem;
    }

    .search-input {
        padding: 8px 12px;
        border-radius: 20px;
        border: 1px solid #ccc;
        outline: none;
        width: 220px;
        transition: 0.3s;
    }

    .search-input:focus {
        border-color: #000;
        background: #fafafa;
    }

    .btn-primary {
        background: linear-gradient(135deg, #000, #2c2c2c);
        color: #fff;
        border-radius: 25px;
        padding: 8px 16px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }

    .card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    /* === Table Style === */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .admin-table th {
        text-align: left;
        background: #f9fafb;
        color: #111;
        font-weight: 600;
        padding: 12px 10px;
        border-bottom: 2px solid #e5e7eb;
    }

    .admin-table td {
        padding: 10px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
        color: #333;
    }

    .admin-table tr:hover {
        background: #f5f5f5;
        transition: 0.2s;
    }

    /* === Actions === */
    .actions {
        display: flex;
        gap: .5rem;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .action-edit {
        background: #000;
        color: #fff;
    }

    .action-edit:hover {
        background: #333;
    }

    .action-delete {
        background: #dc2626;
        color: #fff;
    }

    .action-delete:hover {
        background: #b91c1c;
    }

    .notice {
        background: #e0f2fe;
        color: #1e40af;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: .75rem;
    }

    /* Scheduled row highlight */
    .scheduled-row td {
        background: linear-gradient(90deg, rgba(217, 249, 233, 0.6), rgba(240, 255, 244, 0.4));
    }
    .scheduled-badge {
        display: inline-block;
        background: #065f46;
        color: #fff;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 0.75rem;
        margin-left: 0.5rem;
        vertical-align: middle;
    }
</style>

<div class="list-header">
    <h2><i class="bi bi-syringe"></i> Vaccination Records</h2>
    <div style="display:flex;gap:.75rem;align-items:center">
        <form method="GET" action="{{ route('admin.vaccinations.index') }}" style="display:inline-block">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search vaccinations or patient..." class="search-input" />
        </form>
        <a href="{{ route('admin.vaccinations.create') }}" class="btn-primary">
            <i class="bi bi-plus-circle"></i> Record Vaccination
        </a>
    </div>
</div>

<div class="card">
    @if(session('success'))
        <div class="notice"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if(isset($vaccinations) && count($vaccinations))
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Vaccine</th>
                    <th>Dose</th>
                    <th>Schedule 1</th>
                    <th>Schedule 2</th>
                    <th>Schedule 3</th>
                    <th>Administered By</th>
                    <th>Notes</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vaccinations as $v)
                    <tr class="{{ optional(optional($v->patient->user)->vaccinationSchedule)->schedule_1 || 
                              optional(optional($v->patient->user)->vaccinationSchedule)->schedule_2 || 
                              optional(optional($v->patient->user)->vaccinationSchedule)->schedule_3 ? 'scheduled-row' : '' }}">
                        <td>{{ $v->id }}</td>
                        <td>{{ $v->patient->name ?? '—' }}</td>
                        <td>{{ $v->date_given ? \Illuminate\Support\Carbon::parse($v->date_given)->format('Y-m-d') : '—' }}</td>
                        <td>{{ $v->vaccine ?? '—' }}</td>
                        <td>{{ $v->dose ?? '—' }}</td>
                        <td>{{ optional(optional($v->patient->user)->vaccinationSchedule)->schedule_1 ? \Illuminate\Support\Carbon::parse(optional($v->patient->user->vaccinationSchedule)->schedule_1)->format('Y-m-d') : '—' }}</td>
                        <td>{{ optional(optional($v->patient->user)->vaccinationSchedule)->schedule_2 ? \Illuminate\Support\Carbon::parse(optional($v->patient->user->vaccinationSchedule)->schedule_2)->format('Y-m-d') : '—' }}</td>
                        <td>{{ optional(optional($v->patient->user)->vaccinationSchedule)->schedule_3 ? \Illuminate\Support\Carbon::parse(optional($v->patient->user->vaccinationSchedule)->schedule_3)->format('Y-m-d') : '—' }}</td>
                        <td>{{ $v->administered_by ?? '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($v->notes, 100, '...') }}</td>
                        <td>
                            <div class="actions">
                                <a class="action-btn action-edit" href="{{ route('admin.vaccinations.edit', $v) }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(optional($v->patient)->user)
                                    <a class="action-btn" href="{{ route('admin.vaccination-schedule.edit', optional($v->patient->user)->id) }}" style="background:#d1fae5;color:#065f46;">
                                        <i class="bi bi-calendar3"></i> 
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('admin.vaccinations.destroy', $v) }}" onsubmit="return confirm('Delete this record?')" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn action-delete" type="submit">
                                        <i class="bi bi-trash"></i> 
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(isset($vaccinations) && method_exists($vaccinations, 'links'))
            <div style="margin-top:.75rem">{{ $vaccinations->links() }}</div>
        @endif
    @else
        <div>No vaccinations yet.</div>
    @endif
</div>
@endsection
