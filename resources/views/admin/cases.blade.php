@extends('admin.layout')

@section('title','Cases')

@section('content')
    {{-- styles centralized in admin.layout --}}

    <div class="list-header">
        <h2 style="margin:0; font-weight:600;">Cases</h2>
        <div style="display:flex;gap:.75rem;align-items:center">
            <form method="GET" action="{{ route('admin.cases.index') }}" style="display:inline-block">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search cases or patient..." class="search-input" />
            </form>
            <a href="{{ route('admin.cases.create') }}" class="btn-primary">
                <i class="fa-solid fa-folder-plus"></i> Create Case
            </a>
        </div>
    </div>

    <div class="card">
        @if(session('success'))
            <div class="notice">{{ session('success') }}</div>
        @endif

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
                            <td>
                                <span style="
                                    background: {{ $c->status == 'resolved' ? '#dcfce7' : ($c->status == 'pending' ? '#fef9c3' : '#fee2e2') }};
                                    color: {{ $c->status == 'resolved' ? '#166534' : ($c->status == 'pending' ? '#854d0e' : '#991b1b') }};
                                    padding: .25rem .5rem;
                                    border-radius: 4px;
                                    font-weight: 500;
                                    font-size: 12px;
                                ">
                                    {{ ucfirst($c->status) }}
                                </span>
                            </td>
                            <td>{{ optional($c->reporter)->name ?? optional($c->reporter)->email ?? '—' }}</td>
                            <td style="max-width:320px">{{ \Illuminate\Support\Str::limit($c->description, 120, '...') }}</td>
                            <td data-no-wrap>
                                <div class="actions">
                                    <a class="action-btn action-edit" href="{{ route('admin.cases.edit', $c) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.cases.destroy', $c) }}" onsubmit="return confirm('Delete this case?')" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-btn action-delete" type="submit">
                                            <i class="fa-solid fa-trash"></i> Delete
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
