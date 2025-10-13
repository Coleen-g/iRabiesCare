@extends('admin.layout')

@section('title','Edit Case')

@section('content')
    <h2>Edit Case</h2>
    <div class="card">
        <form method="POST" action="{{ route('admin.cases.update', $case) }}">
            @csrf
            @method('PUT')
            <div>
                <label>Patient</label>
                <select name="patient_id" required style="width:100%;padding:.5rem;">
                    @foreach($patients as $pt)
                        <option value="{{ $pt->id }}" {{ $pt->id == $case->patient_id ? 'selected' : '' }}>{{ $pt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Date Reported</label>
                <input name="date_reported" type="date" value="{{ $case->date_reported }}" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Status</label>
                <input name="status" value="{{ $case->status }}" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Description</label>
                <textarea name="description" style="width:100%;padding:.5rem;">{{ $case->description }}</textarea>
            </div>
            <div style="margin-top:.5rem;">
                <button type="submit">Update</button>
                <a href="{{ route('admin.cases.index') }}" style="margin-left:1rem;">Back</a>
            </div>
        </form>
    </div>
@endsection
