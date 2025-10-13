@extends('admin.layout')

@section('title','Edit Patient')

@section('content')
    <h2>Edit Patient</h2>
    <div class="card">
        <form method="POST" action="{{ route('admin.patients.update', $patient) }}">
            @csrf
            @method('PUT')
            <div>
                <label>Name</label>
                <input name="name" value="{{ old('name', $patient->name) }}" required style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>DOB</label>
                <input name="dob" type="date" value="{{ old('dob', $patient->dob) }}" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Gender</label>
                <input name="gender" value="{{ old('gender', $patient->gender) }}" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Contact</label>
                <input name="contact" value="{{ old('contact', $patient->contact) }}" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Address</label>
                <textarea name="address" style="width:100%;padding:.5rem;">{{ old('address', $patient->address) }}</textarea>
            </div>
            <div style="margin-top:.5rem;">
                <button type="submit">Update</button>
                <a href="{{ route('admin.patients.index') }}" style="margin-left:1rem;">Back</a>
            </div>
        </form>
    </div>
@endsection
