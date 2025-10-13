@extends('admin.layout')

@section('title','Create Case')

@section('content')
    <h2>Create Case</h2>
    <div class="card">
        <form method="POST" action="{{ route('admin.cases.store') }}">
            @csrf
            <div>
                <label>Patient</label>
                <select name="patient_id" required style="width:100%;padding:.5rem;">
                    @foreach($patients as $pt)
                        <option value="{{ $pt->id }}">{{ $pt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Date Reported</label>
                <input name="date_reported" type="date" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Status</label>
                <input name="status" value="open" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Description</label>
                <textarea name="description" style="width:100%;padding:.5rem;"></textarea>
            </div>
            <div style="margin-top:.5rem;">
                <button type="submit">Create</button>
                <a href="{{ route('admin.cases.index') }}" style="margin-left:1rem;">Back</a>
            </div>
        </form>
    </div>
@endsection
