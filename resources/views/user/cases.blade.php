@extends('user.layout')

@section('title','My Cases')

@section('content')
<style>
    /* Professional cases table layout for user */
    .cases-container {
        background: #f8fafc;
        padding: 1.25rem;
    }

    .cases-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(2,6,23,0.06);
        overflow: hidden;
        border: 1px solid #eef2f7;
    }

    .cases-card-header {
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding:1rem 1.25rem;
        gap:1rem;
        border-bottom:1px solid #f1f5f9;
        background: linear-gradient(90deg, rgba(250,250,252,0.7), rgba(255,255,255,0.6));
    }

    .cases-title { display:flex; align-items:center; gap:.75rem; }
    .cases-title h2 { margin:0; font-size:1.15rem; color:#0f172a; }
    .cases-sub { color:#6b7280; font-size:0.95rem }

    .cases-actions { display:flex; gap:.5rem; align-items:center }
    .cases-actions .btn { padding:.45rem .75rem; border-radius:8px; border:1px solid #e6eef7; background:#fff; cursor:pointer }

    .table-responsive { width:100%; overflow:auto; }
    .admin-table { width:100%; border-collapse:collapse; font-size:14px; min-width:1020px; }
    .admin-table thead th { text-align:left; padding:12px 14px; font-weight:600; color:#0f172a; background:#fbfdff; position:sticky; top:0; z-index:2; border-bottom:1px solid #eef2f7 }
    .admin-table tbody td { padding:12px 14px; vertical-align:middle; border-bottom:1px solid #f3f6f9; color:#111827 }
    .admin-table tbody tr:hover { background: #fbfcfe }

    .col-id { width:70px; color:#475569 }
    .col-date { width:120px }
    .col-status { width:150px }
    .col-exposure { width:120px }
    .col-type { width:140px }
    .col-wounds { width:160px }
    .col-category { width:120px }
    .col-species { width:120px }
    .col-animal-status { width:120px }
    .col-reported { width:160px }
    .col-desc { min-width:240px }

    /* refined status badges */
    .badge { display:inline-block; padding:6px 10px; border-radius:999px; font-weight:600; font-size:13px }
    .badge.pending { background:#fff7ed; color:#c2410c; border:1px solid #fcdca8 }
    .badge.ongoing { background:#e6f5ff; color:#075985; border:1px solid #bfe6ff }
    .badge.completed { background:#ecfdf5; color:#14532d; border:1px solid #b7f5d0 }
    .badge.cancelled { background:#fff1f2; color:#7f1d1d; border:1px solid #fecaca }

    .no-cases { padding:2rem; text-align:center; color:#6b7280 }

    .pagination-wrapper { padding:12px; display:flex; justify-content:center; border-top:1px solid #f1f5f9; background:#fbfdff }

    /* Responsive tweaks */
    @media (max-width:900px) {
        .admin-table { min-width:900px }
        .cases-card-header { flex-direction:column; align-items:flex-start; gap:.5rem }
    }
</style>

<div class="cases-container">
    <div class="cases-header">
        <i class="bi bi-file-earmark-medical"></i>
        <h2>My Cases</h2>
    </div>

    @if(is_countable($cases) && count($cases))
        <div class="cases-card">
            <div class="cases-card-header">
                <div class="cases-title">
                    <i class="bi bi-file-earmark-medical" style="font-size:1.25rem;color:#0ea5a3"></i>
                    <div>
                        <h2>My Cases</h2>
                        @php
                            $total = is_object($cases) && method_exists($cases,'total') ? $cases->total() : (is_countable($cases) ? count($cases) : 0);
                        @endphp
                        <div class="cases-sub">Showing <strong>{{ $total }}</strong> case(s)</div>
                    </div>
                </div>
                <div class="cases-actions">
                    <a href="{{ route('user.cases') }}" class="btn">Refresh</a>
                </div>
            </div>

            <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date Reported</th>
                        <th>Case Status</th>
                        <th>Date of Exposure</th>
                        <th>Type of Exposure</th>
                        <th>Location of Wounds</th>
                        <th>Category</th>
                        <th>Species</th>
                        <th>Animal Status</th>
                        <th>Reported By</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cases as $c)
                        @php
                            $rawStatus = strtolower($c->status ?? '');
                            // map to user-friendly classes
                            if (in_array($rawStatus, ['resolved','completed','closed'])) {
                                $statusClass = 'completed';
                            } elseif (in_array($rawStatus, ['ongoing','in progress','in_progress','open'])) {
                                $statusClass = 'ongoing';
                            } elseif (in_array($rawStatus, ['cancelled','rejected'])) {
                                $statusClass = 'cancelled';
                            } else {
                                $statusClass = 'pending';
                            }
                        @endphp
                        <tr>
                            <td class="col-id">{{ $c->id }}</td>
                            <td class="col-date">{{ $c->date_reported ? \Illuminate\Support\Carbon::parse($c->date_reported)->format('Y-m-d') : '—' }}</td>
                            <td class="col-status"><span class="badge {{ $statusClass }}">{{ ucfirst($c->status ?? $statusClass) }}</span></td>
                            <td class="col-exposure">{{ $c->exposure_date ? \Illuminate\Support\Carbon::parse($c->exposure_date)->format('Y-m-d') : ($c->patient->exposure_date ? \Illuminate\Support\Carbon::parse($c->patient->exposure_date)->format('Y-m-d') : '—') }}</td>
                            <td class="col-type">{{ $c->exposure_type ?? ($c->patient->exposure_type ?? '—') }}</td>
                            <td class="col-wounds">{{ $c->wounds_location ?? '—' }}</td>
                            <td class="col-category">{{ $c->category ?? '—' }}</td>
                            <td class="col-species">{{ $c->animal_species ?? ($c->patient->animal ?? '—') }}</td>
                            <td class="col-animal-status">{{ $c->animal_status ?? '—' }}</td>
                            <td class="col-reported">{{ optional($c->reporter)->name ?? optional($c->reporter)->email ?? '—' }}</td>
                            <td class="col-desc">{{ \Illuminate\Support\Str::limit($c->description, 220, '...') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            @if(method_exists($cases, 'links'))
                <div class="pagination-wrapper">{{ $cases->links() }}</div>
            @endif
        </div>
    @else
        <p class="no-cases">No cases found.</p>
    @endif
</div>
@endsection
