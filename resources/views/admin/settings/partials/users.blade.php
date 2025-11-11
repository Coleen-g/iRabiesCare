<div style="margin-bottom:1rem">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
        <h3 style="margin:0;display:flex;align-items:center;gap:.5rem"><i class="bi bi-people-fill"></i> User Management</h3>
        {{-- Manage Accounts button kept for quick access but list below is primary navigation --}}
        @if (\Illuminate\Support\Facades\Route::has('admin.users.index'))
            <a href="{{ route('admin.users.index') }}" class="btn ghost">Manage Accounts</a>
        @else
            <a href="#" class="btn ghost">Manage Accounts</a>
        @endif
    </div>

    <div class="muted" style="margin-bottom:.5rem">Add/edit accounts, reset passwords and assign roles.</div>

    {{-- Scrollable list (no cards) --}}
    <div style="max-height:220px; overflow-y:auto; border:1px solid var(--border); border-radius:8px; background:#fff;">
        <ul style="list-style:none;margin:0;padding:0">
            {{-- Add Health Staff item --}}
            <li style="border-bottom:1px solid #eef2f7">
                @if (\Illuminate\Support\Facades\Route::has('admin.health-staffs.create'))
                    <a href="{{ route('admin.health-staffs.create') }}" class="d-block" style="display:flex;align-items:center;gap:.75rem;padding:0.85rem 1rem;text-decoration:none;color:inherit">
                        <span style="width:2.2rem;height:2.2rem;display:flex;align-items:center;justify-content:center;border-radius:6px;background:#f3f4f6"><i class="bi bi-person-plus"></i></span>
                        <div>
                            <div style="font-weight:600">Add Health Staff</div>
                            <div style="font-size:.85rem;color:#6b7280">Create a new health staff account</div>
                        </div>
                    </a>
                @else
                    <div class="d-block" style="display:flex;align-items:center;gap:.75rem;padding:0.85rem 1rem;color:#9ca3af">
                        <span style="width:2.2rem;height:2.2rem;display:flex;align-items:center;justify-content:center;border-radius:6px;background:#f3f4f6"><i class="bi bi-person-plus"></i></span>
                        <div>
                            <div style="font-weight:600">Add Health Staff</div>
                            <div style="font-size:.85rem;color:#9ca3af">Route not available</div>
                        </div>
                    </div>
                @endif
            </li>

            {{-- Open Patients item --}}
            <li style="border-bottom:1px solid #eef2f7">
                @if (\Illuminate\Support\Facades\Route::has('admin.patients.index'))
                    <a href="{{ route('admin.patients.index') }}" class="d-block" style="display:flex;align-items:center;gap:.75rem;padding:0.85rem 1rem;text-decoration:none;color:inherit">
                        <span style="width:2.2rem;height:2.2rem;display:flex;align-items:center;justify-content:center;border-radius:6px;background:#f3f4f6"><i class="bi bi-people"></i></span>
                        <div>
                            <div style="font-weight:600">Open Patients</div>
                            <div style="font-size:.85rem;color:#6b7280">View and manage patient records</div>
                        </div>
                    </a>
                @else
                    <div class="d-block" style="display:flex;align-items:center;gap:.75rem;padding:0.85rem 1rem;color:#9ca3af">
                        <span style="width:2.2rem;height:2.2rem;display:flex;align-items:center;justify-content:center;border-radius:6px;background:#f3f4f6"><i class="bi bi-people"></i></span>
                        <div>
                            <div style="font-weight:600">Open Patients</div>
                            <div style="font-size:.85rem;color:#9ca3af">Route not available</div>
                        </div>
                    </div>
                @endif
            </li>

            {{-- Placeholder for future user actions (e.g., reset passwords) --}}
            <li>
                <a href="#" onclick="alert('Open the user management table to perform password resets and role assignments.')" style="display:flex;align-items:center;gap:.75rem;padding:0.85rem 1rem;text-decoration:none;color:inherit">
                    <span style="width:2.2rem;height:2.2rem;display:flex;align-items:center;justify-content:center;border-radius:6px;background:#f3f4f6"><i class="bi bi-key-fill"></i></span>
                    <div>
                        <div style="font-weight:600">Reset Passwords / Assign Roles</div>
                        <div style="font-size:.85rem;color:#6b7280">Open a user entry in the management table</div>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    <div class="small-note" style="margin-top:.6rem">To reset user passwords or assign roles, open the user management table and use the action menu.</div>
</div>
