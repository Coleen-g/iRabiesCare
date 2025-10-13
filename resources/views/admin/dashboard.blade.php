@extends('admin.layout')

@section('title','Dashboard')

@section('content')
    <h1>Admin Dashboard</h1>
    <div class="card">
        <p>Welcome, {{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
        <p>Role: {{ auth()->user()->role }}</p>
        <div style="margin-top:1rem; display:flex; gap:1rem;">
            <div style="flex:1;padding:.75rem;border-radius:6px;background:#fff;">
                <strong>Patients</strong>
                <div>0</div>
            </div>
            <div style="flex:1;padding:.75rem;border-radius:6px;background:#fff;">
                <strong>Cases</strong>
                <div>0</div>
            </div>
            <div style="flex:1;padding:.75rem;border-radius:6px;background:#fff;">
                <strong>Vaccinations</strong>
                <div>0</div>
            </div>
        </div>
    </div>
@endsection