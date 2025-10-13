@extends('user.layout')

@section('title','My Profile')

@section('content')
    <h1>My Profile</h1>
    <div class="card">
        <p>Name: {{ $user->name }}</p>
        <p>Email: {{ $user->email }}</p>
        <p>Role: {{ $user->role }}</p>
    </div>
@endsection
