@extends('admin.layout')

@section('title','Create Patient')

@section('content')
    <h2>Create Patient</h2>
    <div class="card">
        <form method="POST" action="{{ route('admin.patients.store') }}">
            @csrf
            <div>
                <label>Name</label>
                <input name="name" required style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>DOB</label>
                <input name="dob" type="date" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Gender</label>
                <input name="gender" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Contact</label>
                <input name="contact" style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <label>Address</label>
                <textarea name="address" style="width:100%;padding:.5rem;"></textarea>
            </div>
            <div style="margin-top:.5rem;">
                <button type="submit">Create</button>
                <a href="{{ route('admin.patients.index') }}" style="margin-left:1rem;">Back</a>
            </div>
        </form>
    </div>
@endsection
