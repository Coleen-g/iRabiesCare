@extends('admin.layout')

@section('title','Patients')

@section('content')
    <style>
        .list-header { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1rem }
        .search-input { padding:.5rem .75rem; border:1px solid #e5e7eb; border-radius:6px; width:240px }
        .btn-primary { background:#2563eb; color:#fff; padding:.5rem .75rem; border-radius:6px; text-decoration:none }
        .btn-ghost { background:transparent; color:#374151; padding:.4rem .6rem; border-radius:6px; border:1px solid transparent }
        table.admin-table { width:100%; border-collapse:collapse; }
        table.admin-table th, table.admin-table td { padding:.75rem; text-align:left; border-bottom:1px solid #f3f4f6 }
        .actions { display:flex; gap:.5rem; align-items:center }
        .action-edit { background:#f3f4f6; padding:.35rem .6rem; border-radius:6px; color:#111; text-decoration:none }
        .action-delete { background:#fee2e2; padding:.35rem .6rem; border-radius:6px; color:#7f1d1d; border:0 }
        .notice { padding:.5rem; background:#ecfccb; border-radius:4px; margin-bottom:.75rem }
    </style>

    <div class="list-header">
        <h2 style="margin:0">Patients</h2>
        <div style="display:flex;gap:.75rem;align-items:center">
            <form method="GET" action="{{ route('admin.patients.index') }}" style="display:inline-block">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search patients" class="search-input" />
            </form>
            <a href="{{ route('admin.patients.create') }}" class="btn-primary">Create Patient</a>
        </div>
    </div>

    <div class="card">
        @if(session('success'))<div class="notice">{{ session('success') }}</div>@endif
    @if(isset($patients) && count($patients))
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th style="width:180px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->contact }}</td>
                            <td>
                                <div class="actions">
                                    <a class="action-edit" href="{{ route('admin.patients.edit', $p) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.patients.destroy', $p) }}" onsubmit="return confirm('Delete this patient?')" style="display:inline">
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

            @if(isset($patients) && method_exists($patients, 'links'))
                <div style="margin-top:.75rem">{{ $patients->links() }}</div>
            @endif
        @else
            <div>No patients yet.</div>
        @endif
    </div>
@endsection
