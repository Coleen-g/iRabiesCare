@extends('user.layout')

@section('title', 'Edit Profile')

@section('content')
    <style>
        /* reuse profile styles */
        body { background: linear-gradient(120deg, #e8f5e9 0%, #f1f8e9 100%); }
        .edit-container { max-width: 1000px; margin: 2rem auto; padding: 1rem; }
        .edit-card { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 6px 18px rgba(0,0,0,0.06); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 1.5rem; }
        .form-group { display:flex; flex-direction:column; }
        .form-group label { font-weight:600; margin-bottom:0.4rem; }
        .form-group input, .form-group textarea, .form-group select { padding:0.6rem; border:1px solid #e5e7eb; border-radius:8px; }
        .form-actions { display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1rem }
        .btn-primary { background: linear-gradient(90deg, #388e3c 0%, #43a047 100%); color:#fff; padding:0.6rem 1rem; border-radius:8px; text-decoration:none; border:none }
        .btn-secondary { background:#f3f4f6; color:#111; padding:0.6rem 1rem; border-radius:8px; border:none }
        @media(max-width:700px) { .form-grid { grid-template-columns: 1fr; } }
    </style>

    <div class="edit-container">
        <div class="edit-card">
            <h2>Edit Profile</h2>
            <form method="POST" action="{{ route('user.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label>Name</label>
                        <input name="name" value="{{ old('name', $user->name) }}" required />
                        @error('name')<div style="color:#b91c1c">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input name="email" type="email" value="{{ old('email', $user->email) }}" required />
                        @error('email')<div style="color:#b91c1c">{{ $message }}</div>@enderror
                    </div>

                    {{-- Patient fields if available --}}
                    @php $patient = optional($user)->patient; @endphp
                    <div class="form-group">
                        <label>Phone</label>
                        <input name="contact" value="{{ old('contact', $patient->contact ?? '') }}" />
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input name="dob" type="date" value="{{ old('dob', $patient->dob ?? '') }}" />
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <input name="gender" value="{{ old('gender', $patient->gender ?? '') }}" />
                    </div>
                    <div class="form-group">
                        <label>Clinic</label>
                        <input name="clinic" value="{{ old('clinic', $patient->clinic ?? '') }}" />
                    </div>

                    <div class="form-group" style="grid-column:1/3;">
                        <label>Address</label>
                        <textarea name="address">{{ old('address', $patient->address ?? '') }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('user.profile') }}" class="btn-secondary">Cancel</a>
                    <button class="btn-primary" type="submit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
