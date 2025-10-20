@extends('admin.layout')

@section('title','Vaccinations')

@section('content')
    {{-- styles centralized in admin.layout --}}

    <div class="list-header">
        <h2 style="margin:0; font-weight:600;">Vaccinations</h2>
        <div style="display:flex;gap:.75rem;align-items:center">
            <form method="GET" action="{{ route('admin.vaccinations.index') }}" style="display:inline-block">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search vaccinations or patient..." class="search-input" />
            </form>
            <a href="{{ route('admin.vaccinations.create') }}" class="btn-primary">
                <i class="fa-solid fa-syringe"></i> Record Vaccination
            </a>
        </div>
    </div>

    <div class="card">
        @if(session('success'))
            <div class="notice">{{ session('success') }}</div>
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
                                    <a class="action-btn action-edit" href="{{ route('admin.vaccinations.edit', $v) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.vaccinations.destroy', $v) }}" onsubmit="return confirm('Delete this record?')" style="display:inline">
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

            @if(isset($vaccinations) && method_exists($vaccinations, 'links'))
                <div style="margin-top:.75rem">{{ $vaccinations->links() }}</div>
            @endif
        @else
            <div>No vaccinations yet.</div>
        @endif
    </div>
@endsection
