@extends('user.layout')

@section('title','My Cases')

@section('content')
    <h1>My Cases</h1>
    <div class="card">
        @if($cases->count())
            <ul>
                @foreach($cases as $c)
                    <li>{{ $c->date_reported }} - {{ $c->status }} - {{ $c->description }}</li>
                @endforeach
            </ul>
        @else
            <p>No cases found.</p>
        @endif
    </div>
@endsection
