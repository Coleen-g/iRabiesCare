@extends('admin.layout')

@section('title','Record Vaccination')

@section('content')
    <h2>Record Vaccination</h2>
    <div class="card">
        <form method="POST" action="{{ route('admin.vaccinations.store') }}">
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
                <label>Date Given</label>
                <input name="date_given" type="date" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Vaccine</label>
                <input name="vaccine" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Dose</label>
                <input name="dose" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Administered By</label>
                <input name="administered_by" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Notes</label>
                <textarea name="notes" style="width:100%;padding:.5rem;"></textarea>
            </div>
            <div style="margin-top:.5rem;">
                <button type="submit">Record</button>
                <a href="{{ route('admin.vaccinations.index') }}" style="margin-left:1rem;">Back</a>
            </div>
        </form>
    </div>
@endsection
