@extends('admin.layout')

@section('title','Record Vaccination')

@section('content')
    <h2 style="margin-bottom:.5rem">Record Vaccination</h2>

    <div class="card" style="padding:1.25rem">
        <form method="POST" action="{{ route('admin.vaccinations.store') }}">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div>
                    <label class="muted">Patient</label>
                    <select name="patient_id" required class="searchable-patient-select" style="width:100%;padding:.6rem;border:1px solid #e6e9ef;border-radius:6px">
                        <option value="">-- select patient --</option>
                        @foreach($patients as $pt)
                            <option value="{{ $pt->id }}" {{ old('patient_id') == $pt->id ? 'selected' : '' }}>{{ $pt->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <div class="muted" style="color:#b91c1c;margin-top:.25rem">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="muted">Date Given</label>
                    <input name="date_given" type="date" value="{{ old('date_given') }}" style="width:100%;padding:.6rem;border:1px solid #e6e9ef;border-radius:6px" />
                    @error('date_given') <div class="muted" style="color:#b91c1c;margin-top:.25rem">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="muted">Vaccine</label>
                    <input name="vaccine" value="{{ old('vaccine') }}" placeholder="e.g. Rabivax" style="width:100%;padding:.6rem;border:1px solid #e6e9ef;border-radius:6px" />
                    @error('vaccine') <div class="muted" style="color:#b91c1c;margin-top:.25rem">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="muted">Dose</label>
                    <input name="dose" value="{{ old('dose') }}" placeholder="e.g. 0.5 mL" style="width:100%;padding:.6rem;border:1px solid #e6e9ef;border-radius:6px" />
                    @error('dose') <div class="muted" style="color:#b91c1c;margin-top:.25rem">{{ $message }}</div> @enderror
                </div>

                <div style="grid-column:1 / -1">
                    <label class="muted">Administered By</label>
                    <input name="administered_by" value="{{ old('administered_by') }}" placeholder="Staff name or clinic" style="width:100%;padding:.6rem;border:1px solid #e6e9ef;border-radius:6px" />
                    @error('administered_by') <div class="muted" style="color:#b91c1c;margin-top:.25rem">{{ $message }}</div> @enderror
                </div>

                <div style="grid-column:1 / -1">
                    <label class="muted">Notes</label>
                    <textarea name="notes" rows="4" style="width:100%;padding:.6rem;border:1px solid #e6e9ef;border-radius:6px">{{ old('notes') }}</textarea>
                    @error('notes') <div class="muted" style="color:#b91c1c;margin-top:.25rem">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top:1rem;display:flex;gap:.5rem;align-items:center">
                <button type="submit" class="btn-primary" style="border:0;padding:.6rem .9rem;border-radius:8px">Record Vaccination</button>
                <a href="{{ route('admin.vaccinations.index') }}" class="btn" style="background:#f3f4f6;border-radius:8px;padding:.5rem .75rem">Cancel</a>
            </div>
        </form>
    </div>
@endsection
