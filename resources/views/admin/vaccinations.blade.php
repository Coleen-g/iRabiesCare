@extends('admin.layout')

@section('title','Vaccinations')

@section('content')
    <style>
        .list-header { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1rem }
        .search-input { padding:.45rem .6rem; border:1px solid #e5e7eb; border-radius:6px; width:220px }
        .btn-primary { background:#2563eb; color:#fff; padding:.45rem .6rem; border-radius:6px; text-decoration:none; font-size:13px }
        table.admin-table { width:100%; border-collapse:collapse; font-size:13px; }
        table.admin-table th, table.admin-table td { padding:.45rem .5rem; text-align:left; border-bottom:1px solid #f3f4f6; vertical-align:middle }
        table.admin-table td { white-space:normal; word-break:break-word; max-width:240px }
        table.admin-table th[data-no-wrap], table.admin-table td[data-no-wrap] { white-space:nowrap; max-width:none }
        .actions { display:flex; gap:.4rem; align-items:center }
        .action-edit { background:#f3f4f6; padding:.3rem .5rem; border-radius:6px; color:#111; text-decoration:none; font-size:13px }
        .action-delete { background:#fee2e2; padding:.3rem .5rem; border-radius:6px; color:#7f1d1d; border:0; font-size:13px }
        .notice { padding:.45rem; background:#ecfccb; border-radius:4px; margin-bottom:.6rem; font-size:13px }
        @media (max-width: 768px) {
            table.admin-table th, table.admin-table td { padding:.35rem .4rem; font-size:12px }
            .search-input { width:160px }
        }
    </style>

    <div class="list-header">
        <h2 style="margin:0">Vaccinations</h2>
        <div style="display:flex;gap:.75rem;align-items:center">
            <form method="GET" action="{{ route('admin.vaccinations.index') }}" style="display:inline-block">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search vaccinations or patient" class="search-input" />
            </form>
            <a href="{{ route('admin.vaccinations.create') }}" class="btn-primary">Record Vaccination</a>
        </div>
    </div>

    <div class="card">
        @if(session('success'))<div class="notice">{{ session('success') }}</div>@endif
    @if(isset($vaccinations) && count($vaccinations))
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Vaccine</th>
                        <th>Dose</th>
                        <th>Administered By</th>
                        <th>Notes</th>
                        <th style="width:180px" data-no-wrap>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vaccinations as $v)
                        <tr>
                            <td>{{ $v->id }}</td>
                            <td>{{ $v->patient->name ?? '—' }}</td>
                            <td>{{ $v->date_given ? \Illuminate\Support\Carbon::parse($v->date_given)->format('Y-m-d') : '—' }}</td>
                            <td>{{ $v->vaccine ?? '—' }}</td>
                            <td>{{ $v->dose ?? '—' }}</td>
                            <td>{{ $v->administered_by ?? '—' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($v->notes, 100, '...') }}</td>
                            <td data-no-wrap>
                                <div class="actions">
                                    <a class="action-edit" href="{{ route('admin.vaccinations.edit', $v) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.vaccinations.destroy', $v) }}" onsubmit="return confirm('Delete this record?')" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-delete" type="submit">Delete</button>
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
