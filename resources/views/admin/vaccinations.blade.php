@extends('admin.layout')

@section('title','Vaccinations')

@section('content')
<style>
    /* === Unified Black & White Dashboard Theme === */
    body {
        font-family: "Poppins", sans-serif;
        background-color: #f9fafb;
        color: #111827;
        margin: 0;
        padding: 0;
    }

    .list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .list-header h2 {
        font-size: 1.4rem;
        font-weight: 600;
        color: #111;
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .list-header h2 i {
        background: linear-gradient(135deg, #000, #2c2c2c);
        color: #fff;
        padding: .6rem;
        border-radius: 10px;
        font-size: 1rem;
    }

    .search-input {
        padding: 8px 12px;
        border-radius: 20px;
        border: 1px solid #ccc;
        outline: none;
        width: 220px;
        transition: 0.3s;
    }

    .search-input:focus {
        border-color: #000;
        background: #fafafa;
    }

    .btn-primary {
        background: linear-gradient(135deg, #000, #2c2c2c);
        color: #fff;
        border-radius: 25px;
        padding: 8px 16px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }

    .card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    /* === Table Style === */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .admin-table th {
        text-align: left;
        background: #f9fafb;
        color: #111;
        font-weight: 600;
        padding: 12px 10px;
        border-bottom: 2px solid #e5e7eb;
    }

    .admin-table td {
        padding: 10px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
        color: #333;
    }

    .admin-table tr:hover {
        background: #f5f5f5;
        transition: 0.2s;
    }

    /* === Actions === */
    .actions {
        display: flex;
        gap: .5rem;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .action-edit {
        background: #000;
        color: #fff;
    }

    .action-edit:hover {
        background: #333;
    }

    .action-delete {
        background: #dc2626;
        color: #fff;
    }

    .action-delete:hover {
        background: #b91c1c;
    }

    .notice {
        background: #e0f2fe;
        color: #1e40af;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: .75rem;
    }

    /* Scheduled row highlight - keep subtle border only so per-schedule cell colors can take effect */
    .scheduled-row td {
        /* Avoid setting a background here because it conflicts with per-cell classes
           like .td-done/.td-missed (specificity/override issues). Use a subtle left
           border to indicate scheduled rows without masking cell colors. */
        border-left: 4px solid rgba(34,197,94,0.06);
    }
    .scheduled-badge {
        display: inline-block;
        background: #065f46;
        color: #fff;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 0.75rem;
        margin-left: 0.5rem;
        vertical-align: middle;
    }
    .remarks-pill { display:inline-flex;align-items:center;gap:.5rem;padding:.25rem .5rem;border-radius:999px;margin-left:.5rem;font-size:.8rem }
    .remarks-icon { font-size:0.85rem;opacity:.9 }
    .remarks-text { color:#0f172a }
    .badge-completed { background:#d1fae5;color:#065f46 }
    .badge-missed { background:#fee2e2;color:#991b1b }
    .badge-pending { background:#fff7ed;color:#92400e }
    /* remark cell backgrounds: done -> blue, missed -> red, pending -> amber */
    /* Match schedule-edit feedback colors and don't change text color */
     /* Make these selectors element+class so they have at least the same specificity
         as the `.scheduled-row td` rule. This ensures the per-schedule cell backgrounds
         show up correctly. */
      /* Use stronger, fully opaque backgrounds so they don't look transparent
          when the row hover background shows through. These are readable and
          still match the schedule-edit color palette. */
      td.td-done { background: #bfdbfe; color: inherit; }
      td.td-missed { background: #fecaca; color: inherit; }
      td.td-pending { background: #fed7aa; color: inherit; }

      /* Ensure the hover effect does not override colored status cells. Only
          apply the hover background to cells that don't have a status class. */
      .admin-table tr:hover td:not(.td-done):not(.td-missed):not(.td-pending) {
            background: #f5f5f5;
      }
    .remarks-text-inline { display:block; margin-top:8px; font-size:0.95rem; font-weight:500; }
</style>

<div class="list-header">
    <h2><i class="bi bi-syringe"></i> Vaccination Records</h2>
    <div style="display:flex;gap:.75rem;align-items:center">
        <form method="GET" action="{{ route('admin.vaccinations.index') }}" style="display:inline-block;display:flex;align-items:center;gap:.5rem">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search vaccinations or patient..." class="search-input" />
            <select name="overall_remarks" onchange="this.form.submit()" style="min-width:180px;padding:8px 10px;border-radius:20px;border:1px solid #ccc;">
                <option value="" {{ request('overall_remarks') == '' ? 'selected' : '' }}>All remarks</option>
                <option value="Completed" {{ request('overall_remarks') === 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Incomplete" {{ request('overall_remarks') === 'Incomplete' ? 'selected' : '' }}>Incomplete</option>
                <option value="Missed All Schedule" {{ request('overall_remarks') === 'Missed All Schedule' ? 'selected' : '' }}>Missed All Schedules</option>
            </select>
        </form>
        <a href="{{ route('admin.vaccinations.create') }}" class="btn-primary">
            <i class="bi bi-plus-circle"></i> Record Vaccination
        </a>
    </div>
</div>

<div class="card">
    @if(session('success'))
        <div class="notice"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if(isset($vaccinations) && count($vaccinations))
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Vaccine</th>
                    <th>Dose</th>
                    <th>Schedule 1</th>
                    <th>Schedule 2</th>
                    <th>Schedule 3</th>
                    <th>Administered By</th>
                    <th>Remarks</th>
                    <th style="width:180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vaccinations as $v)
                    <tr data-user-id="{{ optional($v->patient->user)->id }}" data-vaccination-id="{{ $v->id }}" class="{{ optional(optional($v->patient->user)->vaccinationSchedule)->schedule_1 || 
                              optional(optional($v->patient->user)->vaccinationSchedule)->schedule_2 || 
                              optional(optional($v->patient->user)->vaccinationSchedule)->schedule_3 ? 'scheduled-row' : '' }}">
                        <td>{{ $v->id }}</td>
                        <td>{{ $v->patient->name ?? '—' }}</td>
                        <td>{{ $v->date_given ? \Illuminate\Support\Carbon::parse($v->date_given)->format('Y-m-d') : '—' }}</td>
                        <td>{{ $v->vaccine ?? '—' }}</td>
                        <td>{{ $v->dose ?? '—' }}</td>
                        @php
                            $vs = optional(optional($v->patient->user)->vaccinationSchedule);
                            $s1 = $vs && $vs->schedule_1 ? \Illuminate\Support\Carbon::parse($vs->schedule_1)->format('Y-m-d') : null;
                            $s2 = $vs && $vs->schedule_2 ? \Illuminate\Support\Carbon::parse($vs->schedule_2)->format('Y-m-d') : null;
                            $s3 = $vs && $vs->schedule_3 ? \Illuminate\Support\Carbon::parse($vs->schedule_3)->format('Y-m-d') : null;
                            $s1_status = $vs->schedule_1_status ?? 'pending';
                            $s2_status = $vs->schedule_2_status ?? 'pending';
                            $s3_status = $vs->schedule_3_status ?? 'pending';
                            $s1_remarks = trim($vs->schedule_1_remarks ?? '') ?: null;
                            $s2_remarks = trim($vs->schedule_2_remarks ?? '') ?: null;
                            $s3_remarks = trim($vs->schedule_3_remarks ?? '') ?: null;

                            $badgeClass = function($status) {
                                if ($status === 'completed') return 'badge-completed';
                                if ($status === 'missed') return 'badge-missed';
                                return 'badge-pending';
                            };
                        @endphp

                        <td class="{{ $s1_status === 'completed' ? 'td-done' : ($s1_status === 'missed' ? 'td-missed' : '') }}">
                            <div class="schedule-date">{{ $s1 ?? '—' }}</div>
                        </td>
                        <td class="{{ $s2_status === 'completed' ? 'td-done' : ($s2_status === 'missed' ? 'td-missed' : '') }}">
                            <div class="schedule-date">{{ $s2 ?? '—' }}</div>
                        </td>
                        <td class="{{ $s3_status === 'completed' ? 'td-done' : ($s3_status === 'missed' ? 'td-missed' : '') }}">
                            <div class="schedule-date">{{ $s3 ?? '—' }}</div>
                        </td>
                        <td>{{ $v->administered_by ?? '—' }}</td>
                        @php
                            // Show the overall schedule remark (from the user's vaccination schedule)
                            // instead of per-vaccination notes/remarks. This keeps schedule-level notes
                            // consistent across admin/health staff views.
                            $overall_schedule_remarks = trim(optional($vs)->overall_remarks ?? '');
                            $consolidated_status = strtolower($v->status ?? 'pending');
                        @endphp
                        <td class="{{ $overall_schedule_remarks ? ($consolidated_status === 'completed' ? 'td-done' : ($consolidated_status === 'missed' ? 'td-missed' : 'td-pending')) : '' }}" title="{{ $overall_schedule_remarks ?? '' }}">
                            @if($overall_schedule_remarks)
                                <div class="remarks-text-inline">{{ \Illuminate\Support\Str::limit($overall_schedule_remarks, 100) }}</div>
                            @else
                                <span class="muted">No remarks</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a class="action-btn action-edit" href="{{ route('admin.vaccinations.edit', $v) }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(optional($v->patient)->user)
                                    <a class="action-btn" href="{{ route('admin.vaccination-schedule.edit', optional($v->patient->user)->id) }}" style="background:#d1fae5;color:#065f46;">
                                        <i class="bi bi-calendar3"></i> 
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('admin.vaccinations.destroy', $v) }}" onsubmit="return confirm('Delete this record?')" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-btn action-delete" type="submit">
                                        <i class="bi bi-trash"></i> 
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {{-- overall schedule remark row removed: keep only per-row remark cell above --}}
                @endforeach
            </tbody>
        </table>

        @if(isset($vaccinations) && method_exists($vaccinations, 'links'))
            <div style="margin-top:.75rem">{{ $vaccinations->links() }}</div>
        @endif
    @else
        <div>No vaccinations yet.</div>
    @endif
</div>
    <script>
        // Poll for schedule updates and update remark pills in-place.
        (function(){
            // Resolve updates endpoint only if the named route exists to avoid RouteNotFoundException
            let route = '';
            @if (\Illuminate\Support\Facades\Route::has('admin.vaccination-schedules.updates'))
                route = @json(route('admin.vaccination-schedules.updates'));
            @endif
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value || '';

            async function fetchUpdates() {
                try {
                    const rows = Array.from(document.querySelectorAll('tr[data-user-id]'));
                    const userIds = [...new Set(rows.map(r => r.getAttribute('data-user-id')).filter(Boolean))];
                    if (!userIds.length) return;
                    if (!route) return; // no updates endpoint available

                    const fd = new FormData();
                    userIds.forEach(id => fd.append('user_ids[]', id));

                    const res = await fetch(route, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: fd,
                        credentials: 'same-origin'
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (!data.success || !data.schedules) return;

                    // update DOM for each schedule pill in its specific schedule cell
                    const cellMap = { schedule_1: 6, schedule_2: 7, schedule_3: 8 };
                    Object.entries(data.schedules).forEach(([userId, schedule]) => {
                        ['schedule_1','schedule_2','schedule_3'].forEach(k => {
                            const remarks = schedule[`${k}_remarks`] || null;
                            const status = schedule[`${k}_status`] || 'pending';
                            const row = document.querySelector(`tr[data-user-id="${userId}"]`);
                            if (!row) return;
                            const td = row.querySelector(`td:nth-child(${cellMap[k]})`);
                            if (!td) return;

                            // set appropriate td class based on status (color only)
                            td.classList.remove('td-done','td-missed','td-pending');
                            if (status === 'completed') td.classList.add('td-done');
                            else if (status === 'missed') td.classList.add('td-missed');
                            // do NOT insert inline remark text or set title — only color the cell
                        });
                        // overall schedule remark rows removed — only update per-schedule cell classes
                    });
                } catch (err) {
                    console.error('Error fetching schedule updates', err);
                }
            }

            // initial fetch and then poll every 8s
            fetchUpdates();
            setInterval(fetchUpdates, 8000);
        })();
    </script>
@endsection
