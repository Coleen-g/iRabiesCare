@extends('admin.layout')

@section('title','Edit Vaccination')

@section('content')
    <h2>Edit Vaccination</h2>
    <div class="card">
        <form method="POST" action="{{ route('admin.vaccinations.update', $vaccination) }}">
            @csrf
            @method('PUT')
            <div>
                <label>Patient</label>
                <select name="patient_id" required class="searchable-patient-select" style="width:100%;padding:.5rem;">
                    @foreach($patients as $pt)
                        <option value="{{ $pt->id }}" {{ $pt->id == $vaccination->patient_id ? 'selected' : '' }}>{{ $pt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Date Given</label>
                <input name="date_given" type="date" value="{{ $vaccination->date_given }}" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Vaccine</label>
                <input name="vaccine" value="{{ $vaccination->vaccine }}" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Dose</label>
                <input name="dose" value="{{ $vaccination->dose }}" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Administered By</label>
                <input name="administered_by" value="{{ $vaccination->administered_by }}" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Notes</label>
                <textarea name="notes" style="width:100%;padding:.5rem;">{{ $vaccination->notes }}</textarea>
            </div>
            <div style="margin-top:.5rem;">
                <button type="submit">Update</button>
                <a href="{{ route('admin.vaccinations.index') }}" style="margin-left:1rem;">Back</a>
            </div>
        </form>
    </div>
@endsection
