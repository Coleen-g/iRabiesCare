@extends('user.layout')

@section('title','Dashboard')

@section('content')
    <h1>My Dashboard</h1>
    <div class="card">
        @if($patient)
            <p>Patient record: {{ $patient->name }}</p>
            <p>Contact: {{ $patient->contact }}</p>
        @else
            <p>No patient record linked to your account yet.</p>
        @endif

        <h3>Recent Cases</h3>
        <ul>
            @forelse($cases as $c)
                <li>{{ $c->date_reported }} - {{ $c->status }} - {{ $c->description }}</li>
            @empty
                <li>No cases</li>
            @endforelse
        </ul>

        <h3>Vaccinations</h3>
        <ul>
            @forelse($vaccinations as $v)
                <li>{{ $v->date_given }} - {{ $v->vaccine }} ({{ $v->dose }})</li>
            @empty
                <li>No vaccinations</li>
            @endforelse
        </ul>
    </div>
@endsection
