@extends('admin.layout')

@section('title','Vaccinations')

@section('content')
    <style>
        .list-header { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1rem }
        .search-input { padding:.5rem .75rem; border:1px solid #e5e7eb; border-radius:6px; width:240px }
        .btn-primary { background:#2563eb; color:#fff; padding:.5rem .75rem; border-radius:6px; text-decoration:none }
        table.admin-table { width:100%; border-collapse:collapse; }
        table.admin-table th, table.admin-table td { padding:.75rem; text-align:left; border-bottom:1px solid #f3f4f6 }
        .actions { display:flex; gap:.5rem; align-items:center }
        .action-edit { background:#f3f4f6; padding:.35rem .6rem; border-radius:6px; color:#111; text-decoration:none }
        .action-delete { background:#fee2e2; padding:.35rem .6rem; border-radius:6px; color:#7f1d1d; border:0 }
        .notice { padding:.5rem; background:#ecfccb; border-radius:4px; margin-bottom:.75rem }
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
                        <th>Patient</th>
                        <th>Vaccine</th>
                        <th>Date</th>
                        <th style="width:180px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vaccinations as $v)
                        <tr>
                            <td>{{ $v->patient->name ?? '—' }}</td>
                            <td>{{ $v->vaccine }}</td>
                            <td>{{ $v->date_given }}</td>
                            <td>
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
