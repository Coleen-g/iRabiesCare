@extends('health_staff.layout')

@section('title', 'Edit Vaccination Schedule')

@section('content')
<div class="container mt-4">
    <h2>Edit Vaccination Schedule for {{ $user->name }}</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('health_staff.vaccination-schedule.update', $user->id) }}">
        @csrf
        @method('PUT')
        <style>
            .hs-schedule-row { display:flex;gap:1rem;align-items:flex-start;margin-bottom:1rem }
            .hs-schedule-col { flex:1 }
            .hs-status-badge { padding:6px 10px;border-radius:8px;color:#fff;font-weight:600 }
            .hs-completed { background:#2563eb }
            .hs-missed { background:#dc2626 }
            .hs-pending { background:#6b7280 }
            .hs-actions { display:flex;flex-direction:column;gap:.5rem;align-items:flex-end;min-width:220px }
            .hs-small-btn { padding:.4rem .6rem;border-radius:6px;border:none;color:#fff;cursor:pointer }
            .hs-done { background:#2563eb }
            .hs-miss { background:#dc2626 }
            .hs-save { background:#10b981 }
            .hs-remarks { width:100%;min-height:56px;border:1px solid #e5e7eb;border-radius:6px;padding:.5rem }
            .hs-schedule-cell.passed { box-shadow: inset 0 0 0 2px rgba(37,99,235,0.06) }
        </style>

        <div id="schedule-form">
            <div class="mb-3 hs-schedule-row hs-schedule-cell" data-key="schedule_1">
                <div class="hs-schedule-col">
                    <label for="schedule_1" class="form-label">Next Vaccination #1</label>
                    <input type="date" class="form-control" id="schedule_1" name="schedule_1" value="{{ $schedule->schedule_1 ?? '' }}">
                    <input type="hidden" name="schedule_1_status" id="schedule_1_status" value="{{ strtolower($schedule->schedule_1_status ?? 'pending') }}">
                    <div style="margin-top:.5rem">
                        <label class="form-label">Remarks</label>
                        <textarea name="schedule_1_remarks" id="schedule_1_remarks" class="hs-remarks">{{ $schedule->schedule_1_remarks ?? '' }}</textarea>
                    </div>
                </div>
                <div class="hs-actions">
                    @php $s1 = strtolower($schedule->schedule_1_status ?? 'pending'); @endphp
                    <div id="badge_schedule_1" class="hs-status-badge {{ $s1 === 'completed' ? 'hs-completed' : ($s1 === 'missed' ? 'hs-missed' : 'hs-pending') }}">
                        {{ ucfirst($s1) }}
                    </div>
                    <div style="display:flex;gap:.5rem">
                        <button type="button" onclick="markSchedule('schedule_1','missed')" class="hs-small-btn hs-miss">Mark Missed</button>
                        <button type="button" onclick="markSchedule('schedule_1','completed')" class="hs-small-btn hs-done">Mark Done</button>
                    </div>
                </div>
            </div>

            <div class="mb-3 hs-schedule-row hs-schedule-cell" data-key="schedule_2">
                <div class="hs-schedule-col">
                    <label for="schedule_2" class="form-label">Next Vaccination #2</label>
                    <input type="date" class="form-control" id="schedule_2" name="schedule_2" value="{{ $schedule->schedule_2 ?? '' }}">
                    <input type="hidden" name="schedule_2_status" id="schedule_2_status" value="{{ strtolower($schedule->schedule_2_status ?? 'pending') }}">
                    <div style="margin-top:.5rem">
                        <label class="form-label">Remarks</label>
                        <textarea name="schedule_2_remarks" id="schedule_2_remarks" class="hs-remarks">{{ $schedule->schedule_2_remarks ?? '' }}</textarea>
                    </div>
                </div>
                <div class="hs-actions">
                    @php $s2 = strtolower($schedule->schedule_2_status ?? 'pending'); @endphp
                    <div id="badge_schedule_2" class="hs-status-badge {{ $s2 === 'completed' ? 'hs-completed' : ($s2 === 'missed' ? 'hs-missed' : 'hs-pending') }}">
                        {{ ucfirst($s2) }}
                    </div>
                    <div style="display:flex;gap:.5rem">
                        <button type="button" onclick="markSchedule('schedule_2','missed')" class="hs-small-btn hs-miss">Mark Missed</button>
                        <button type="button" onclick="markSchedule('schedule_2','completed')" class="hs-small-btn hs-done">Mark Done</button>
                    </div>
                </div>
            </div>

            <div class="mb-3 hs-schedule-row hs-schedule-cell" data-key="schedule_3">
                <div class="hs-schedule-col">
                    <label for="schedule_3" class="form-label">Next Vaccination #3</label>
                    <input type="date" class="form-control" id="schedule_3" name="schedule_3" value="{{ $schedule->schedule_3 ?? '' }}">
                    <input type="hidden" name="schedule_3_status" id="schedule_3_status" value="{{ strtolower($schedule->schedule_3_status ?? 'pending') }}">
                    <div style="margin-top:.5rem">
                        <label class="form-label">Remarks</label>
                        <textarea name="schedule_3_remarks" id="schedule_3_remarks" class="hs-remarks">{{ $schedule->schedule_3_remarks ?? '' }}</textarea>
                    </div>
                </div>
                <div class="hs-actions">
                    @php $s3 = strtolower($schedule->schedule_3_status ?? 'pending'); @endphp
                    <div id="badge_schedule_3" class="hs-status-badge {{ $s3 === 'completed' ? 'hs-completed' : ($s3 === 'missed' ? 'hs-missed' : 'hs-pending') }}">
                        {{ ucfirst($s3) }}
                    </div>
                    <div style="display:flex;gap:.5rem">
                        <button type="button" onclick="markSchedule('schedule_3','missed')" class="hs-small-btn hs-miss">Mark Missed</button>
                        <button type="button" onclick="markSchedule('schedule_3','completed')" class="hs-small-btn hs-done">Mark Done</button>
                    </div>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:.5rem;margin-top:1rem">
                </div>

            <div style="margin-top:1rem;">
                @php
                    $overall = trim($schedule->overall_remarks ?? '');
                    $selectVal = $overall === 'Completed' ? 'Completed' : ($overall === 'Incomplete' ? 'Incomplete' : '');
                @endphp
                <label class="form-label" for="overall_remarks">Overall Schedule Status</label>
                <div style="margin-top:.5rem;max-width:320px;">
                    <select id="overall_remarks" name="overall_remarks" class="form-control">
                        <option value="" {{ $selectVal === '' ? 'selected' : '' }}>None</option>
                        <option value="Incomplete" {{ $selectVal === 'Incomplete' ? 'selected' : '' }}>Incomplete</option>
                        <option value="Completed" {{ $selectVal === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:.5rem;margin-top:1rem">
                <button type="submit" class="btn btn-success">Save Schedule</button>
                <a href="{{ route('health_staff.patients.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <script>
            const adminName = @json(optional(auth()->user())->name ?? 'Admin');
            function markSchedule(key, status) {
                // set hidden status
                const statusInput = document.getElementById(key + '_status');
                if (!statusInput) return;
                statusInput.value = status;

                // set remarks textarea: append a short audit line
                const ta = document.getElementById(key + '_remarks');
                const now = new Date();
                const timestamp = now.toLocaleString();
                const note = `Marked as ${status} by ${adminName} on ${timestamp}`;
                if (ta) {
                    // overwrite remarks with the audit line (do not append)
                    ta.value = note;
                }

                // update badge color immediately
                const badge = document.getElementById('badge_' + key);
                if (badge) {
                    badge.classList.remove('hs-completed','hs-missed','hs-pending');
                    if (status === 'completed') badge.classList.add('hs-completed');
                    else if (status === 'missed') badge.classList.add('hs-missed');
                    else badge.classList.add('hs-pending');
                    badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                }

                // highlight cell background for visual feedback
                const cell = document.querySelector('.hs-schedule-cell[data-key="' + key + '"]');
                if (cell) {
                    cell.style.transition = 'background-color .18s ease';
                    if (status === 'completed') cell.style.background = '#eff6ff';
                    else if (status === 'missed') cell.style.background = '#fef2f2';
                    else cell.style.background = '#f8fafc';
                }

                // submit via AJAX to avoid full-page redirect
                const form = document.querySelector('form[action][method]');
                if (form) {
                    const url = form.action;
                    const csrfInput = form.querySelector('input[name="_token"]');
                    const csrf = csrfInput ? csrfInput.value : '';

                    const fd = new FormData(form);
                    // ensure method spoofing to PUT
                    fd.set('_method', 'PUT');

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: fd,
                        credentials: 'same-origin'
                    }).then(res => res.json().then(data => ({ok:res.ok, data}))).then(({ok,data}) => {
                        if (ok && data && data.success) {
                            // reflect server-saved remarks (fresh schedule)
                            const s = data.schedule || {};
                            // update only the schedule cell that was acted on
                            const k = key;
                            const ta = document.getElementById(k + '_remarks');
                            if (ta && s[`${k}_remarks`] !== undefined && s[`${k}_remarks`] !== null) {
                                ta.value = s[`${k}_remarks`];
                            }
                            const badge = document.getElementById('badge_' + k);
                            if (badge && s[`${k}_status`] !== undefined && s[`${k}_status`] !== null) {
                                badge.classList.remove('hs-completed','hs-missed','hs-pending');
                                if (s[`${k}_status`] === 'completed') badge.classList.add('hs-completed');
                                else if (s[`${k}_status`] === 'missed') badge.classList.add('hs-missed');
                                else badge.classList.add('hs-pending');
                                badge.textContent = (s[`${k}_status`] || 'pending').charAt(0).toUpperCase() + (s[`${k}_status`] || 'pending').slice(1);
                            }
                            // update overall_remarks select if server returned it
                            const overallSel = document.getElementById('overall_remarks');
                            if (overallSel && s['overall_remarks'] !== undefined && s['overall_remarks'] !== null) {
                                overallSel.value = s['overall_remarks'];
                            }
                        } else {
                            console.warn('Failed to save schedule', data);
                        }
                    }).catch(err => {
                        console.error('Error saving schedule', err);
                    });
                }
            }
        </script>
    </form>
</div>
@endsection
