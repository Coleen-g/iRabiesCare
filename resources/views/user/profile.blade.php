@extends('user.layout')

@section('title','My Profile')

@section('content')
    <style>
        .profile-grid { display:grid; grid-template-columns: 1fr 220px; gap:1rem }
        .profile-row { margin-bottom:.5rem }
        .label { color:#6b7280; font-weight:600 }
    </style>

    <div class="card profile-grid">
        <div>
            <h2 style="margin-top:0">{{ $user->name }}</h2>
            <div class="profile-row"><div class="label">Email</div><div>{{ $user->email }}</div></div>
            <div class="profile-row"><div class="label">Role</div><div>{{ $user->role }}</div></div>
        </div>
        <div>
            <div style="display:flex;flex-direction:column;gap:.5rem">
                <a class="btn btn-primary" href="{{ \Illuminate\Support\Facades\Route::has('user.profile.edit') ? route('user.profile.edit') : '#' }}">Edit Profile</a>
            </div>
        </div>
    </div>
@endsection
