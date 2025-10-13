@extends('admin.layout')

@section('title','Patients')

@section('content')
    <h2>Patients</h2>

    <div style="margin-bottom:1rem;">
        <a href="{{ route('admin.patients.create') }}">Create New Patient</a>
    </div>

    <div class="card">
        @if(session('success'))<div style="padding:.5rem;background:#ecfccb;border-radius:4px;margin-bottom:.5rem">{{ session('success') }}</div>@endif
        @if(isset($patients) && $patients->count())
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:.5rem">Name</th>
                        <th style="text-align:left;padding:.5rem">Contact</th>
                        <th style="text-align:left;padding:.5rem">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $p)
                        <tr>
                            <td style="padding:.5rem">{{ $p->name }}</td>
                            <td style="padding:.5rem">{{ $p->contact }}</td>
                            <td style="padding:.5rem">
                                <a href="{{ route('admin.patients.edit', $p) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.patients.destroy', $p) }}" style="display:inline">@csrf @method('DELETE')<button type="submit" onclick="return confirm('Delete?')">Delete</button></form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top:.75rem">{{ $patients->links() }}</div>
        @else
            <div>No patients yet.</div>
        @endif
    </div>
@endsection
