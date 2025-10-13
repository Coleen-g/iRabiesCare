@extends('admin.layout')

@section('title','Cases')

@section('content')
    <h2>Cases</h2>

    <div style="margin-bottom:1rem;">
        <a href="{{ route('admin.cases.create') }}">Create New Case</a>
    </div>

    <div class="card">
        @if(session('success'))<div style="padding:.5rem;background:#ecfccb;border-radius:4px;margin-bottom:.5rem">{{ session('success') }}</div>@endif
        @if(isset($cases) && $cases->count())
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:.5rem">Patient</th>
                        <th style="text-align:left;padding:.5rem">Status</th>
                        <th style="text-align:left;padding:.5rem">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cases as $c)
                        <tr>
                            <td style="padding:.5rem">{{ $c->patient->name ?? '—' }}</td>
                            <td style="padding:.5rem">{{ $c->status }}</td>
                            <td style="padding:.5rem">
                                <a href="{{ route('admin.cases.edit', $c) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.cases.destroy', $c) }}" style="display:inline">@csrf @method('DELETE')<button type="submit" onclick="return confirm('Delete?')">Delete</button></form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top:.75rem">{{ $cases->links() }}</div>
        @else
            <div>No cases yet.</div>
        @endif
    </div>
@endsection
