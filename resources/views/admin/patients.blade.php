@extends('admin.layout')

@section('title','Patients')

@section('content')
    {{-- styles centralized in admin.layout --}}

    <div class="list-header">
        <h2 style="margin:0; font-weight:600;">Patients</h2>
        <div style="display:flex;gap:.75rem;align-items:center">
            <form method="GET" action="{{ route('admin.patients.index') }}" style="display:inline-block">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search patients..." class="search-input" />
            </form>
            <a href="{{ route('admin.patients.create') }}" class="btn-primary">
                <i class="fa-solid fa-user-plus"></i> Create Patient
            </a>
        </div>
    </div>

    <div class="card">
        @if(session('success'))
            <div class="notice">{{ session('success') }}</div>
        @endif

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
                        <th style="width:190px" data-no-wrap>Actions</th>
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
                                    <a class="action-btn action-edit" href="{{ route('admin.patients.edit', $p) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>

                                    @if(is_null($p->user_id))
                                        <form method="POST" action="{{ route('admin.patients.generate', $p) }}" style="display:inline">
                                            @csrf
                                            <button class="action-btn action-regen" type="submit">
                                                <i class="fa-solid fa-gear"></i> Generate
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.patients.regenerate', $p) }}" style="display:inline">
                                            @csrf
                                            <button class="action-btn action-regen" type="submit">
                                                <i class="fa-solid fa-arrows-rotate"></i> Regenerate
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.patients.destroy', $p) }}" onsubmit="return confirm('Delete this patient?')" style="display:inline">
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

            @if(isset($patients) && method_exists($patients, 'links'))
                <div style="margin-top:.75rem">{{ $patients->links() }}</div>
            @endif
        @else
            <div>No patients yet.</div>
        @endif
    </div>
@endsection
