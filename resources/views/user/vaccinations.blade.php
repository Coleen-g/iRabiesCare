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
                    <div style="display:flex;justify-content:space-between;gap:1rem;align-items:center">
                        <div>
                            <div style="font-weight:600">{{ $v->date_given ? \Illuminate\Support\Carbon::parse($v->date_given)->format('Y-m-d') : '—' }} — {{ $v->vaccine }}</div>
                            @if($v->notes)
                                <div class="muted">{{ Str::limit($v->notes, 140) }}</div>
                            @endif
                        </div>
                        <div class="muted">Dose: {{ $v->dose ?? '—' }}</div>
                    </div>
                </div>
            @endforeach

            @if(method_exists($vaccinations, 'links'))
                <div style="margin-top:.75rem">{{ $vaccinations->links() }}</div>
            @endif
        @else
            <p class="muted">No vaccinations found.</p>
        @endif
    </div>
@endsection
