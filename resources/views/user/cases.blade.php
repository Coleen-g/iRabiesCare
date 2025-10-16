@extends('user.layout')

@section('title','My Cases')

@section('content')
<style>
    /* ─── CASE LIST STYLING ───────────────────────────── */
    .cases-container {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }

    .cases-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 1.25rem;
    }

    .cases-header i {
        color: #2563eb;
        font-size: 1.3rem;
    }

    .cases-header h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1e3a8a;
        margin: 0;
    }

    .case-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.85rem 0;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }

    .case-item:last-child {
        border-bottom: none;
    }

    .case-item:hover {
        background: #f9fafb;
    }

    .case-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .case-date {
        font-weight: 600;
        color: #111827;
    }

    .case-desc {
        color: #6b7280;
        font-size: 0.95rem;
        max-width: 600px;
    }

    .case-status {
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 20px;
        padding: 0.25rem 0.75rem;
        text-transform: capitalize;
        text-align: center;
        min-width: 90px;
    }

    /* ─── STATUS COLORS ───────────────────────────── */
    .case-status.pending { background: #fff7ed; color: #c2410c; border: 1px solid #fdba74; }
    .case-status.ongoing { background: #e0f2fe; color: #0369a1; border: 1px solid #7dd3fc; }
    .case-status.completed { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
    .case-status.cancelled { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

    .no-cases {
        color: #6b7280;
        text-align: center;
        padding: 1rem 0;
    }

    .pagination-wrapper {
        margin-top: 1rem;
        display: flex;
        justify-content: center;
    }
</style>

<div class="cases-container">
    <div class="cases-header">
        <i class="bi bi-file-earmark-medical"></i>
        <h2>My Cases</h2>
    </div>

    @if(is_countable($cases) && count($cases))
        @foreach($cases as $c)
            @php
                $statusClass = match(strtolower($c->status)) {
                    'pending' => 'pending',
                    'ongoing' => 'ongoing',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                    default => 'pending',
                };
            @endphp

            <div class="case-item">
                <div class="case-info">
                    <div class="case-date">
                        {{ $c->date_reported ? \Illuminate\Support\Carbon::parse($c->date_reported)->format('Y-m-d') : '—' }}
                    </div>
                    <div class="case-desc">{{ Str::limit($c->description, 160) }}</div>
                </div>

                <div class="case-status {{ $statusClass }}">
                    {{ ucfirst($c->status) }}
                </div>
            </div>
        @endforeach

        @if(method_exists($cases, 'links'))
            <div class="pagination-wrapper">
                {{ $cases->links() }}
            </div>
        @endif
    @else
        <p class="no-cases">No cases found.</p>
    @endif
</div>
@endsection
