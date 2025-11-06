@extends('health_staff.layout')

@section('title', 'Cases')

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

    /* === Status Tags === */
    .status {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: .3rem .6rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 12px;
    }

    .status i { font-size: .75rem; }

    .status-resolved {
        background: #dcfce7;
        color: #166534;
    }

    .status-pending {
        background: #fef9c3;
        color: #92400e;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
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
</style>

<div class="list-header">
    <h2><i class="bi bi-clipboard-data"></i> Case Management</h2>
    <div style="display:flex;gap:.75rem;align-items:center">
    <form method="GET" action="{{ route('health_staff.cases.index') }}" style="display:inline-block">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search cases or patient..." class="search-input" />
        </form>
    <a href="{{ route('health_staff.cases.create') }}" class="btn-primary">
            <i class="bi bi-folder-plus"></i> Create Case
        </a>
    </div>
</div>

<div class="card">
    @if(session('success'))
        <div class="notice"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if(isset($cases) && count($cases))
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Date Reported</th>
                    <th>Case Status</th>
                    <th>Date of Exposure</th>
                    <th>Type of Exposure</th>
                    <th>Location of Wounds</th>
                    <th>Category</th>
                    <th>Species</th>
                    <th>Animal Status</th>
                    <th>Reported By</th>
                    <th>Description</th>
                    <th style="width:160px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cases as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->patient->name ?? '—' }}</td>
                        <td>{{ $c->date_reported ? \Illuminate\Support\Carbon::parse($c->date_reported)->format('Y-m-d') : '—' }}</td>
                        <td>
                            <span class="status 
                                {{ $c->status == 'resolved' ? 'status-resolved' : ($c->status == 'pending' ? 'status-pending' : 'status-rejected') }}">
                                <i class="bi {{ $c->status == 'resolved' ? 'bi-check-circle' : ($c->status == 'pending' ? 'bi-hourglass-split' : 'bi-x-circle') }}"></i>
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>
                        @php
                            $exposureDate = $c->exposure_date ?? ($c->patient->exposure_date ?? null);
                        @endphp
                        <td>{{ $exposureDate ? \Illuminate\Support\Carbon::parse($exposureDate)->format('Y-m-d') : '—' }}</td>
                        <td>{{ $c->exposure_type ?? ($c->patient->exposure_type ?? '—') }}</td>
                        <td>{{ $c->wounds_location ?? '—' }}</td>
                        <td>{{ $c->category ?? '—' }}</td>
                        <td>{{ $c->animal_species ?? ($c->patient->animal ?? '—') }}</td>
                        <td>{{ $c->animal_status ?? '—' }}</td>
                        <td>{{ optional($c->reporter)->name ?? optional($c->reporter)->email ?? '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($c->description, 100, '...') }}</td>
                        <td>
                            <div class="actions">
                                <a class="action-btn action-edit" href="{{ route('health_staff.cases.edit', $c) }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('health_staff.cases.destroy', $c) }}" onsubmit="return confirm('Delete this case?')" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn action-delete" type="submit">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(isset($cases) && method_exists($cases, 'links'))
            <div style="margin-top:.75rem">{{ $cases->links() }}</div>
        @endif
    @else
        <div>No cases yet.</div>
    @endif
</div>
@endsection
