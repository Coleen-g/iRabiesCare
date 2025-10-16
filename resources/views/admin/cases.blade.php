@extends('admin.layout')

@section('title','Cases')

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
        <h2 style="margin:0">Cases</h2>
        <div style="display:flex;gap:.75rem;align-items:center">
            <form method="GET" action="{{ route('admin.cases.index') }}" style="display:inline-block">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search cases or patient" class="search-input" />
            </form>
            <a href="{{ route('admin.cases.create') }}" class="btn-primary">Create Case</a>
        </div>
    </div>

    <div class="card">
        @if(session('success'))<div class="notice">{{ session('success') }}</div>@endif
    @if(isset($cases) && count($cases))
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Date Reported</th>
                        <th>Status</th>
                        <th>Reported By</th>
                        <th>Description</th>
                        <th style="width:180px" data-no-wrap>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cases as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ $c->patient->name ?? '—' }}</td>
                            <td>{{ $c->date_reported ? \Illuminate\Support\Carbon::parse($c->date_reported)->format('Y-m-d') : '—' }}</td>
                            <td>{{ ucfirst($c->status) }}</td>
                            <td>{{ optional($c->reporter)->name ?? optional($c->reporter)->email ?? '—' }}</td>
                            <td style="max-width:320px">{{ \Illuminate\Support\Str::limit($c->description, 120, '...') }}</td>
                            <td data-no-wrap>
                                <div class="actions">
                                    <a class="action-edit" href="{{ route('admin.cases.edit', $c) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.cases.destroy', $c) }}" onsubmit="return confirm('Delete this case?')" style="display:inline">
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

            @if(isset($cases) && method_exists($cases, 'links'))
                <div style="margin-top:.75rem">{{ $cases->links() }}</div>
            @endif
        @else
            <div>No cases yet.</div>
        @endif
    </div>
@endsection
