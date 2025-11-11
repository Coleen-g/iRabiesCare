@extends('user.layout')

@section('title','My Vaccinations')

@section('content')
    <style>
        .vaccination-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .vaccination-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(15, 23, 42, 0.08);
        }

        .vaccination-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .vaccination-item:last-child {
            border-bottom: none;
        }

        .vaccine-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .vaccine-icon {
            background: #dbeafe;
            color: #1e40af;
            padding: 0.6rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .vaccine-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
        }
        .vaccine-date {
            color: #475569;
            font-size: 0.875rem;
        }
        .vaccine-notes {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        

        .vaccine-dose {
            background: #eff6ff;
            color: #1e40af;
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        /* Status badges */
        .status-badge {
            display:inline-block;padding:0.25rem 0.5rem;border-radius:6px;font-weight:600;font-size:0.8rem;color:#fff;
        }
        .status-completed { background: #2563eb; } /* blue */
        .status-missed { background: #dc2626; } /* red */
        .status-pending { background: #6b7280; } /* gray */

        .admin-actions { display:flex;gap:.5rem;align-items:center;margin-top:.5rem }
        .admin-actions form { display:inline-flex; gap:.5rem; align-items:center }
        .admin-actions .btn { padding:.35rem .6rem;border-radius:6px;border:none;cursor:pointer }
        .btn-save { background:#2563eb;color:#fff }
        .btn-missed { background:#dc2626;color:#fff }
        .btn-done { background:#06b6d4;color:#fff }
        .remarks-input { padding:.4rem .6rem;border:1px solid #d1d5db;border-radius:6px }

        .page-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .page-header h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }
        .page-header .icon {
            background: #dbeafe;
            color: #1e40af;
            padding: 0.6rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .muted {
            color: #6b7280;
        }
        /* Scheduled row subtle indicator and per-cell status colors to match admin/health staff views */
        .scheduled-row td { border-left: 4px solid rgba(34,197,94,0.06); }
        td.td-done { background: #bfdbfe; color: inherit; }
        td.td-missed { background: #fecaca; color: inherit; }
        td.td-pending { background: #fed7aa; color: inherit; }
        /* prevent hover from washing out status cells */
        table tr:hover td:not(.td-done):not(.td-missed):not(.td-pending) { background: #f5f5f5; }
    </style>

    <div class="card vaccination-card">
        <div class="page-header">
            <div class="icon">
                <i class="fas fa-syringe"></i>
            </div>
            <h2>My Vaccinations</h2>
        </div>

        {{-- Vaccination Schedule Table --}}
        @php
            $schedule = \App\Models\VaccinationSchedule::where('user_id', auth()->id())->first();
        @endphp
        <div style="background:#ffffff;border-radius:12px;padding:1.2rem;margin-bottom:1.5rem;border:1px solid #e5e7eb;">
            <div style="margin-bottom:1rem;">
                <strong style="color:#065f46;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;">
                    <i class="fas fa-calendar-check"></i> Your Vaccination Schedule
                </strong>
            </div>
            @if($schedule && ($schedule->schedule_1 || $schedule->schedule_2 || $schedule->schedule_3))
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="background:#f0fdf4;color:#065f46;padding:0.75rem;text-align:left;border-bottom:2px solid #dcfce7;font-weight:600;">First Schedule</th>
                                <th style="background:#f0fdf4;color:#065f46;padding:0.75rem;text-align:left;border-bottom:2px solid #dcfce7;font-weight:600;">Second Schedule</th>
                                <th style="background:#f0fdf4;color:#065f46;padding:0.75rem;text-align:left;border-bottom:2px solid #dcfce7;font-weight:600;">Third Schedule</th>
                                <th style="background:#f0fdf4;color:#065f46;padding:0.75rem;text-align:left;border-bottom:2px solid #dcfce7;font-weight:600;">Remarks</th>
                            </tr>
                        </thead>
                    <tbody>
                                <tr data-user-id="{{ auth()->id() }}">
                                @php
                                    $s1 = $schedule->schedule_1 ? \Illuminate\Support\Carbon::parse($schedule->schedule_1)->format('M d, Y') : null;
                                    $s2 = $schedule->schedule_2 ? \Illuminate\Support\Carbon::parse($schedule->schedule_2)->format('M d, Y') : null;
                                    $s3 = $schedule->schedule_3 ? \Illuminate\Support\Carbon::parse($schedule->schedule_3)->format('M d, Y') : null;
                                    $s1_status = $schedule->schedule_1_status ?? 'pending';
                                    $s2_status = $schedule->schedule_2_status ?? 'pending';
                                    $s3_status = $schedule->schedule_3_status ?? 'pending';
                                    // overall remarks only (not per-schedule) - read from vaccination_schedules.overall_remarks
                                    $overall_remarks = $schedule->overall_remarks ?? null;
                                @endphp
                                <td class="{{ $s1_status === 'completed' ? 'td-done' : ($s1_status === 'missed' ? 'td-missed' : '') }}" style="padding:1rem 0.75rem;border-bottom:1px solid #f0fdf4;">
                                    @if($s1)
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <i class="fas fa-calendar-day" style="color:#065f46"></i>
                                            <span>{{ $s1 }}</span>
                                        </div>
                                    @else
                                        <span style="color:#6b7280;">Not scheduled</span>
                                    @endif
                                </td>
                                <td class="{{ $s2_status === 'completed' ? 'td-done' : ($s2_status === 'missed' ? 'td-missed' : '') }}" style="padding:1rem 0.75rem;border-bottom:1px solid #f0fdf4;">
                                    @if($s2)
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <i class="fas fa-calendar-day" style="color:#065f46"></i>
                                            <span>{{ $s2 }}</span>
                                        </div>
                                    @else
                                        <span style="color:#6b7280;">Not scheduled</span>
                                    @endif
                                </td>
                                <td class="{{ $s3_status === 'completed' ? 'td-done' : ($s3_status === 'missed' ? 'td-missed' : '') }}" style="padding:1rem 0.75rem;border-bottom:1px solid #f0fdf4;">
                                    @if($s3)
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <i class="fas fa-calendar-day" style="color:#065f46"></i>
                                            <span>{{ $s3 }}</span>
                                        </div>
                                    @else
                                        <span style="color:#6b7280;">Not scheduled</span>
                                    @endif
                                </td>
                                <td id="user-schedule-remarks" style="padding:1rem 0.75rem;border-bottom:1px solid #f0fdf4;max-width:360px;">
                                    @if($overall_remarks)
                                        <div style="color:#374151;">{{ \Illuminate\Support\Str::limit($overall_remarks, 200) }}</div>
                                    @else
                                        <span style="color:#6b7280;" id="user-no-remarks">No remarks</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align:center;padding:1.5rem;color:#6b7280;">
                    <i class="fas fa-calendar-times" style="font-size:1.5rem;margin-bottom:0.5rem;"></i>
                    <p style="margin:0;">No vaccination schedule has been set yet.</p>
                </div>
            @endif
        </div>

        @if(is_countable($vaccinations) && count($vaccinations))
            @foreach($vaccinations as $v)
                <div class="vaccination-item">
                    <div class="vaccine-info">
                        <div class="vaccine-icon">
                            <i class="fas fa-syringe"></i>
                        </div>
                        <div>
                            <div class="vaccine-title">
                                {{ $v->vaccine ?? 'Unknown Vaccine' }}
                            </div>
                            <div class="vaccine-date">
                                {{ $v->date_given ? \Illuminate\Support\Carbon::parse($v->date_given)->format('M d, Y') : 'No Date Recorded' }}
                            </div>
                            @if(!empty($v->remarks))
                                <div class="vaccine-notes">
                                    <strong>Remarks:</strong> {{ \Illuminate\Support\Str::limit($v->remarks, 180) }}
                                </div>
                            @elseif($v->notes)
                                <div class="vaccine-notes">
                                    {{ Str::limit($v->notes, 120) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.5rem;">
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <div class="vaccine-dose">
                                Dose {{ $v->dose ?? '—' }}
                            </div>
                            @php
                                $status = strtolower($v->status ?? 'pending');
                            @endphp
                            @if($status === 'completed')
                                <span class="status-badge status-completed">Completed</span>
                            @elseif($status === 'missed')
                                <span class="status-badge status-missed">Missed</span>
                            @else
                                <span class="status-badge status-pending">{{ ucfirst($status) }}</span>
                            @endif
                        </div>

                        {{-- Admin controls: mark missed/done and add remarks --}}
                        @if(optional(auth()->user())->role === 'admin')
                            <div class="admin-actions">
                                <form method="POST" action="{{ route('admin.vaccinations.update', $v->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="missed" />
                                    <input type="hidden" name="remarks" value="" />
                                    <button type="submit" class="btn btn-missed" title="Mark missed">Mark Missed</button>
                                </form>

                                <form method="POST" action="{{ route('admin.vaccinations.update', $v->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="completed" />
                                    <input type="hidden" name="remarks" value="" />
                                    <button type="submit" class="btn btn-done" title="Mark completed">Mark Done</button>
                                </form>

                                <form method="POST" action="{{ route('admin.vaccinations.update', $v->id) }}" style="min-width:240px;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{ $v->status ?? 'pending' }}" />
                                    <input type="text" name="remarks" placeholder="Add remarks" class="remarks-input" value="{{ $v->remarks ?? '' }}" />
                                    <button type="submit" class="btn btn-save">Save</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

            @endforeach

            @if(method_exists($vaccinations, 'links'))
                <div style="margin-top:1rem">
                    {{ $vaccinations->links() }}
                </div>
            @endif
        @else
            <p class="muted">No vaccination records found.</p>
        @endif
    </div>

    {{-- Font Awesome for icons --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Poll the schedule updates endpoint so the user's schedule remark and per-schedule cell colors
        // update automatically when an admin/health_staff edits the schedule.
        (function(){
            let route = '';
            @if (\Illuminate\Support\Facades\Route::has('admin.vaccination-schedules.updates'))
                route = @json(route('admin.vaccination-schedules.updates'));
            @endif
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value || '';

            async function fetchUpdate() {
                try {
                    if (!route) return;
                    const userId = '{{ auth()->id() }}';
                    const fd = new FormData();
                    fd.append('user_ids[]', userId);

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
                    const schedule = data.schedules[userId];
                    if (!schedule) return;

                    // update per-schedule td classes (first three tds inside the schedule table row)
                    const row = document.querySelector('tr[data-user-id="' + userId + '"]');
                    if (row) {
                        const cells = row.querySelectorAll('td');
                        // schedule cells are at index 0..2 in this small table
                        ['schedule_1','schedule_2','schedule_3'].forEach((k, idx) => {
                            const status = schedule[`${k}_status`] || 'pending';
                            const cell = cells[idx];
                            if (!cell) return;
                            cell.classList.remove('td-done','td-missed','td-pending');
                            if (status === 'completed') cell.classList.add('td-done');
                            else if (status === 'missed') cell.classList.add('td-missed');
                        });

                        // update overall remarks cell
                        const overall = schedule['overall_remarks'] || '';
                        const remarksTd = document.getElementById('user-schedule-remarks');
                        if (remarksTd) {
                            if (overall && overall.trim() !== '') {
                                remarksTd.innerHTML = `<div style="color:#374151;">${overall.substring(0,200)}</div>`;
                            } else {
                                remarksTd.innerHTML = '<span style="color:#6b7280;" id="user-no-remarks">No remarks</span>';
                            }
                        }
                    }
                } catch (err) {
                    console.error('Error fetching schedule update for user', err);
                }
            }

            // initial fetch and then poll every 8s
            fetchUpdate();
            setInterval(fetchUpdate, 8000);
        })();
    </script>
@endsection
