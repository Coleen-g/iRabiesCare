@extends('user.layout')

@section('title','My Cases')

@section('content')
    <style>
        .list { background:#fff; padding:1rem; border-radius:8px; box-shadow:0 2px 6px rgba(15,23,42,0.04) }
        .item { padding:.5rem 0; border-bottom:1px solid #f3f4f6 }
        .item:last-child { border-bottom:0 }
        .muted { color:#6b7280 }
    </style>

    <div class="card list">
        <h2 style="margin-top:0">My Cases</h2>
        @if(is_countable($cases) && count($cases))
            @foreach($cases as $c)
                <div class="item">
                    <div style="display:flex;justify-content:space-between">
                        <div><strong>{{ $c->date_reported }}</strong> <span class="muted">— {{ ucfirst($c->status) }}</span></div>
                    </div>
                    <div class="muted">{{ Str::limit($c->description, 160) }}</div>
                </div>
            @endforeach
        @else
            <p class="muted">No cases found.</p>
        @endif
    </div>
@endsection
