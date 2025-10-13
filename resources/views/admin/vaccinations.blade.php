@extends('admin.layout')

@section('title','Vaccinations')

@section('content')
    <h2>Vaccinations</h2>

    <div style="margin-bottom:1rem;">
        <a href="{{ route('admin.vaccinations.create') }}">Record Vaccination</a>
    </div>

    <div class="card">
        @if(session('success'))<div style="padding:.5rem;background:#ecfccb;border-radius:4px;margin-bottom:.5rem">{{ session('success') }}</div>@endif
        @if(isset($vaccinations) && $vaccinations->count())
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:.5rem">Patient</th>
                        <th style="text-align:left;padding:.5rem">Vaccine</th>
                        <th style="text-align:left;padding:.5rem">Date</th>
                        <th style="text-align:left;padding:.5rem">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vaccinations as $v)
                        <tr>
                            <td style="padding:.5rem">{{ $v->patient->name ?? '—' }}</td>
                            <td style="padding:.5rem">{{ $v->vaccine }}</td>
                            <td style="padding:.5rem">{{ $v->date_given }}</td>
                            <td style="padding:.5rem">
                                <a href="{{ route('admin.vaccinations.edit', $v) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.vaccinations.destroy', $v) }}" style="display:inline">@csrf @method('DELETE')<button type="submit" onclick="return confirm('Delete?')">Delete</button></form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top:.75rem">{{ $vaccinations->links() }}</div>
        @else
            <div>No vaccinations yet.</div>
        @endif
    </div>
@endsection
