@extends('admin.layout')

@section('title','Patients')

@section('content')
    <style>
        .list-header { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1rem }
        .search-input { padding:.45rem .6rem; border:1px solid #e5e7eb; border-radius:6px; width:220px }
        .btn-primary { background:#2563eb; color:#fff; padding:.45rem .6rem; border-radius:6px; text-decoration:none; font-size:13px }
        .btn-ghost { background:transparent; color:#374151; padding:.35rem .5rem; border-radius:6px; border:1px solid transparent; font-size:13px }
        table.admin-table { width:100%; border-collapse:collapse; font-size:13px; }
        table.admin-table th, table.admin-table td { padding:.45rem .5rem; text-align:left; border-bottom:1px solid #f3f4f6; vertical-align:middle }
        /* make long content wrap instead of expanding cells */
        table.admin-table td { white-space:normal; word-break:break-word; max-width:220px; }
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
                        <th>Username</th>
                        <th>Email</th>
                        @if(auth()->user() && auth()->user()->isAdmin())
                            <th>Plain Password</th>
                        @endif
                        <th style="width:180px" data-no-wrap>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->contact }}</td>
                            <td>{{ optional($p->user)->name ?? '-' }}</td>
                            <td>{{ optional($p->user)->email ?? '-' }}</td>
                            @if(auth()->user() && auth()->user()->isAdmin())
                                <td>{{ optional($p->user)->plain_password ?? '-' }}</td>
                            @endif
                            <td data-no-wrap>
                                <div class="actions">
                                    <a class="action-edit" href="{{ route('admin.patients.edit', $p) }}">Edit</a>
                                    @if(is_null($p->user_id))
                                        <form method="POST" action="{{ route('admin.patients.generate', $p) }}" style="display:inline">
                                            @csrf
                                            <button class="action-edit" type="submit">Generate</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.patients.regenerate', $p) }}" style="display:inline">
                                            @csrf
                                            <button class="action-edit" type="submit">Regenerate</button>
                                        </form>
                                    @endif
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
