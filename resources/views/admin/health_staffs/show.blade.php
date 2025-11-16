@extends('admin.layout')

@section('title', $staff->full_name)

@section('content')
<style>
/* ========== UPGRADED PROFESSIONAL DASHBOARD STYLE FOR HEALTH STAFF ========== */
body { background: #f5f6f7; font-family: "Inter", system-ui, sans-serif; }
.staff-card { background: #fff; border-radius: 14px; box-shadow: 0 8px 20px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; padding: 2rem; max-width: 1150px; margin: 2.5rem auto; transition: 0.3s ease; }
.staff-card:hover { box-shadow: 0 12px 24px rgba(0,0,0,0.08); }
.staff-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
.staff-title { display: flex; align-items: center; gap: 1rem; }
.staff-icon { width: 3rem; height: 3rem; background: #000; border-radius: 12px; color: #fff; font-size: 1.4rem; display: flex; align-items: center; justify-content: center; transition: background 0.3s ease, transform 0.2s ease; }
.staff-icon:hover { background: #111; transform: scale(1.1); }
h2 { font-size: 1.6rem; font-weight: 700; color: #111827; }
.staff-main { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
.staff-info { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem 2rem; }
.staff-info-item strong { color: #4b5563; font-size: 0.9rem; font-weight: 600; display: block; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.3px; }
.staff-info-item div { color: #111827; font-size: 0.95rem; }
.staff-sidebar { background: #111; border-radius: 12px; padding: 1.5rem; color: #f9fafb; display: flex; flex-direction: column; gap: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: fit-content; }
.status-badge { display: inline-block; padding: 0.45rem 1.2rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 600; letter-spacing: 0.3px; }
.status-badge.active { background: #10b98133; color: #10b981; border: 1px solid #10b98155; }
.status-badge.pending { background: #f59e0b22; color: #f59e0b; border: 1px solid #f59e0b44; }
.btn-primary { display: inline-flex; align-items: center; justify-content: center; padding: 10px 20px; background: #000; color: #fff; border-radius: 8px; font-weight: 600; text-decoration: none; transition: 0.3s ease; }
.btn-primary:hover { background: #222; transform: translateY(-2px); }
.staff-sidebar strong { font-size: 0.9rem; color: #e5e7eb; text-transform: uppercase; letter-spacing: 0.3px; }
.staff-sidebar div { font-size: 0.95rem; }
</style>

<div class="staff-card">
    <div class="staff-header">
        <div class="staff-title">
            <div class="staff-icon"><i class="bi bi-person-badge"></i></div>
            <div>
                <h2>{{ $staff->full_name }}</h2>
                <small style="color:#6b7280;font-size:0.95rem;">{{ $staff->position ?? '' }} {{ $staff->department ? ' — ' . $staff->department : '' }}</small>
            </div>
        </div>
        <div style="display:flex;gap:.5rem;align-items:center">
            <button type="button" class="btn-ghost" title="Back" onclick="(function(){ if (history.length > 1) { history.back(); } else { window.location.href = '{{ route('admin.health-staffs.index') }}'; } })()">&larr; Back</button>
            <a href="{{ route('admin.health-staffs.edit', $staff) }}" class="btn-primary">Edit</a>
        </div>
    </div>

    <div class="staff-main">
        <div class="staff-info">
            <div class="staff-info-item">
                <strong>Contact</strong>
                <div>{{ $staff->contact_number ?? '-' }}</div>
            </div>

            <div class="staff-info-item">
                <strong>Email</strong>
                <div>{{ $staff->email ?? ($staff->user->email ?? '-') }}</div>
            </div>

            <div class="staff-info-item">
                <strong>Assigned Facility</strong>
                <div>{{ $staff->assigned_facility ?? '-' }}</div>
            </div>

            <div class="staff-info-item">
                <strong>Role</strong>
                <div>{{ ucfirst(str_replace('_',' ',$staff->role ?? 'health_staff')) }}</div>
            </div>

            <div class="staff-info-item">
                <strong>Department</strong>
                <div>{{ $staff->department ?? '-' }}</div>
            </div>

            <div class="staff-info-item">
                <strong>Status</strong>
                <div>{{ $staff->status ?? 'Active' }}</div>
            </div>

            <div class="staff-info-item" style="grid-column:1/3;">
                <strong>Notes</strong>
                <div>{{ $staff->notes ?? 'No notes' }}</div>
            </div>
        </div>

        <div class="staff-sidebar">
            <div>
                <strong>Status</strong>
                <div style="margin-top:.5rem">
                    <span class="status-badge {{ strtolower($staff->status ?? 'active') }}">
                        {{ $staff->status ?? 'Active' }}
                    </span>
                </div>
            </div>

            <div>
                <strong>Account</strong>
                <div style="margin-top:.5rem">
                    @if($staff->user)
                        <div><strong>Username:</strong> {{ $staff->user->email ?? '-' }}</div>
                        <div><strong>Account Name:</strong> {{ $staff->user->name ?? '-' }}</div>
                        <div><strong>Temp Password:</strong> {{ $staff->user->plain_password ?? '-' }}</div>
                    @else
                        <div>—</div>
                    @endif
                </div>
            </div>

            <div>
                <strong>Registered</strong>
                <div style="margin-top:.5rem">{{ $staff->created_at ? $staff->created_at->format('M d, Y') : '-' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
