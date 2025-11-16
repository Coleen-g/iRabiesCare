@extends('health_staff.layout')
@section('title','Vaccinations')
@section('content')
<style>
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

    .notice {
        background: #e0f2fe;
        color: #1e40af;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: .75rem;
    }

    /* Scheduled row highlight - subtle border so per-schedule cell colors can take effect */
    .scheduled-row td {
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
    td.td-done { background: #bfdbfe; color: inherit; }
    td.td-missed { background: #fecaca; color: inherit; }
    td.td-pending { background: #fed7aa; color: inherit; }

    /* Ensure the hover effect does not override colored status cells. Only apply
       the hover background to cells that don't have a status class. */
    .admin-table tr:hover td:not(.td-done):not(.td-missed):not(.td-pending) {
        background: #f5f5f5;
    }

    /* === Empty State === */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        margin: 0;
        font-size: 0.875rem;
    }
</style>

<div class="list-header">
    <h2>
        <i class="bi bi-syringe" style="background:linear-gradient(135deg,#000,#2c2c2c);color:#fff;padding:.45rem;border-radius:8px;margin-right:.6rem;display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;"></i>
        Vaccination Records
    </h2>
    <div style="display:flex;gap:.75rem;align-items:center">
        <form method="GET" action="{{ route('health_staff.vaccinations.index') }}" style="display:inline-block;display:flex;align-items:center;gap:.5rem">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search vaccinations or patient..." class="search-input" />
            <select name="overall_remarks" onchange="this.form.submit()" style="min-width:180px;padding:8px 10px;border-radius:20px;border:1px solid #ccc;">
                <option value="" {{ request('overall_remarks') == '' ? 'selected' : '' }}>All remarks</option>
                <option value="Completed" {{ request('overall_remarks') === 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Incomplete" {{ request('overall_remarks') === 'Incomplete' ? 'selected' : '' }}>Incomplete</option>
                <option value="Missed All Schedule" {{ request('overall_remarks') === 'Missed All Schedule' ? 'selected' : '' }}>Missed All Schedules</option>
            </select>
        </form>
        <a href="{{ route('health_staff.vaccinations.create') }}" class="btn-primary" style="display:inline-flex;align-items:center">
            <i class="bi bi-plus-circle" style="margin-right:.5rem;font-size:1rem;display:inline-block;vertical-align:middle"></i>
            Record Vaccination
        </a>
    </div>
</div>

<div class="card">
    @if(session('success'))
        <div class="notice" role="status">
            <i class="bi bi-check-circle" style="margin-right:.5rem;vertical-align:middle"></i>
            {{ session('success') }}
        </div>
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
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $shownAny = false; @endphp
                @foreach($vaccinations as $v)
                    @php
                        // ensure this vaccination belongs to a patient assigned to this health staff
                        $isAssigned = false;
                        if ($v->patient) {
                            try {
                                $isAssigned = $v->patient->assignedHealthStaff()->where('users.id', auth()->id())->exists();
                            } catch (\Exception $e) {
                                $isAssigned = false;
                            }
                        }
                        if (! $isAssigned) {
                            continue; // skip rows for patients not assigned to this staff
                        }
                        $shownAny = true;

                        // prepare schedule values and UI pieces
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

                    <tr data-user-id="{{ optional($v->patient->user)->id }}" class="{{ ($s1 || $s2 || $s3) ? 'scheduled-row' : '' }}">
                        <td>{{ $v->id }}</td>
                        <td>{{ $v->patient->name ?? '—' }}</td>
                        <td>{{ $v->date_given ? \Illuminate\Support\Carbon::parse($v->date_given)->format('Y-m-d') : '—' }}</td>
                        <td>{{ $v->vaccine ?? '—' }}</td>
                        <td>{{ $v->dose ?? '—' }}</td>
                        <td class="{{ $s1_status === 'completed' ? 'td-done' : ($s1_status === 'missed' ? 'td-missed' : '') }}">{{ $s1 ?? '—' }}</td>
                        <td class="{{ $s2_status === 'completed' ? 'td-done' : ($s2_status === 'missed' ? 'td-missed' : '') }}">{{ $s2 ?? '—' }}</td>
                        <td class="{{ $s3_status === 'completed' ? 'td-done' : ($s3_status === 'missed' ? 'td-missed' : '') }}">{{ $s3 ?? '—' }}</td>
                        <td>{{ $v->administered_by ?? '—' }}</td>
                        @php
                            // Show overall schedule remark from the patient's vaccination schedule
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
                                <a class="action-btn action-edit" href="{{ route('health_staff.vaccinations.edit', $v) }}" title="Edit">
                                    <i class="bi bi-pencil" style="font-size:1rem;color:inherit"></i>
                                </a>

                                @if(optional($v->patient)->user)
                                    <a class="action-btn" href="{{ route('health_staff.vaccination-schedule.edit', optional($v->patient->user)->id) }}" style="background:#d1fae5;color:#065f46;" title="Edit schedule">
                                        <i class="bi bi-calendar3" style="font-size:1rem;color:inherit"></i>
                                    </a>
                                @endif

                                {{-- delete action removed per request --}}
                            </div>
                        </td>
                    </tr>
                    {{-- overall schedule remark row removed for health staff view; per-row remark cell retained --}}
                @endforeach
            </tbody>
        </table>

        @if(isset($vaccinations) && method_exists($vaccinations, 'links'))
            <div style="margin-top:.75rem">{{ $vaccinations->links() }}</div>
        @endif
    @else
        <div class="empty-state">
            <i class="bi bi-clipboard-x" style="font-size:2.5rem;margin-bottom:1rem;opacity:.6;display:block"></i>
            <p>No vaccinations yet</p>
        </div>
    @endif
</div>
    <script>
        // Poll for schedule updates and update remark pills in-place (health staff view)
        (function(){
            // Resolve updates endpoint only if the named route exists to avoid RouteNotFoundException
            let route = '';
            @if (\Illuminate\Support\Facades\Route::has('admin.vaccination-schedules.updates'))
                route = @json(route('admin.vaccination-schedules.updates'));
            @endif
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value || '';

            function badgeClass(status) {
                if (status === 'completed') return 'badge-completed';
                if (status === 'missed') return 'badge-missed';
                return 'badge-pending';
            }

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

                            // update DOM for each schedule: only toggle td status classes (color only)
                            const cellMap = { schedule_1: 6, schedule_2: 7, schedule_3: 8 };
                            Object.entries(data.schedules).forEach(([userId, schedule]) => {
                                ['schedule_1','schedule_2','schedule_3'].forEach(k => {
                                    const status = schedule[`${k}_status`] || 'pending';
                                    const row = document.querySelector(`tr[data-user-id="${userId}"]`);
                                    if (!row) return;
                                    const td = row.querySelector(`td:nth-child(${cellMap[k]})`);
                                    if (!td) return;

                                    td.classList.remove('td-done','td-missed','td-pending');
                                    if (status === 'completed') td.classList.add('td-done');
                                    else if (status === 'missed') td.classList.add('td-missed');
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