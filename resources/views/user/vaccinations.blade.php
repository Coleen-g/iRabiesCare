@extends('user.layout')

@section('title','My Vaccinations')

@section('content')
    <h1>My Vaccinations</h1>
    <div class="card">
        @if($vaccinations->count())
            <ul>
                @foreach($vaccinations as $v)
                    <li>{{ $v->date_given }} - {{ $v->vaccine }} - {{ $v->dose }}</li>
                @endforeach
            </ul>
        @else
            <p>No vaccinations found.</p>
        @endif
    </div>
@endsection
