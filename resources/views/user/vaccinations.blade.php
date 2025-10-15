@extends('user.layout')

@section('title','My Vaccinations')

@section('content')
    <style>
        .list { background:#fff; padding:1rem; border-radius:8px; box-shadow:0 2px 6px rgba(15,23,42,0.04) }
        .item { padding:.5rem 0; border-bottom:1px solid #f3f4f6 }
        .item:last-child { border-bottom:0 }
        .muted { color:#6b7280 }
    </style>

    <div class="card list">
        <h2 style="margin-top:0">My Vaccinations</h2>
        @if(is_countable($vaccinations) && count($vaccinations))
            @foreach($vaccinations as $v)
                <div class="item">
                    <div style="display:flex;justify-content:space-between">
                        <div>{{ $v->date_given }} — {{ $v->vaccine }}</div>
                        <div class="muted">Dose: {{ $v->dose }}</div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="muted">No vaccinations found.</p>
        @endif
    </div>
@endsection
