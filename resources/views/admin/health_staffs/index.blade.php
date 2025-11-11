@extends('admin.layout')

@section('title', 'Health Staff')

@section('content')
<style>
/* === Base Layout === */
.dashboard-container {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

/* === Header === */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-icon {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #000, #2b2b2b);
    border-radius: 12px;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.header-title h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

/* === Search & Button === */
.search-input {
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    outline: none;
    transition: 0.2s;
}

.search-input:focus {
    border-color: #111;
}

.btn-primary {
    background: linear-gradient(135deg, #000, #2b2b2b);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-primary:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* === Table === */
.table-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th {
    background: #f9fafb;
    text-align: left;
    padding: 1rem;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 600;
    border-bottom: 1px solid #e5e7eb;
    white-space: nowrap;
}

.admin-table td {
    padding: 1rem;
    font-size: 0.875rem;
    color: #111827;
    border-bottom: 1px solid #f3f4f6;
}

.admin-table tr:hover td {
    background: #f9fafb;
}

.actions { display:flex; gap:.5rem }
.btn-action, .action-btn { display:inline-flex; align-items:center; justify-content:center; width:2rem; height:2rem; border-radius:6px; border:none; cursor:pointer }
.btn-action.view { background:#f3f4f6 }
.btn-action.edit { background:#dbeafe }
.action-delete { background:#fee2e2 }

.notice { background:#ecfdf5; color:#065f46; padding:.75rem 1rem; border-radius:8px; margin-bottom:1rem }

.empty-state { text-align:center; padding:2rem 0; color:#6b7280 }

</style>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-title">
            <div class="header-icon"><i class="bi bi-person-badge"></i></div>
            <h1>Health Staff</h1>
        </div>
        <div style="display:flex; gap:.75rem; align-items:center;">
            <form method="GET" action="{{ route('admin.health-staffs.index') }}">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search health staff..." class="search-input" />
            </form>
            <a href="{{ route('admin.health-staffs.create') }}" class="btn-primary">
                <i class="bi bi-person-plus"></i> Add Health Staff
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="notice">{{ session('success') }}</div>
    @endif

    <div class="table-container">
        @if(isset($staff) && count($staff))
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:60px">No.</th>
                        <th>Full Name</th>
                        <th>Contact</th>
                        <th>Username</th>
                        <th>Email</th>
                        @if(auth()->user() && auth()->user()->isAdmin())
                            <th>Password</th>
                        @endif
                        <th style="width:190px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $s)
                        @php
                            $rowNumber = (isset($staff) && method_exists($staff, 'currentPage'))
                                ? ($staff->currentPage() - 1) * $staff->perPage() + $loop->iteration
                                : $loop->iteration;
                        @endphp
                        <tr>
                            <td>{{ $rowNumber }}</td>
                            <td><strong>{{ $s->full_name }}</strong><br><small style="color:#6b7280;">{{ $s->position ?? '' }}</small></td>
                            <td>{{ $s->contact_number ?? '-' }}</td>
                            <td>{{ optional($s->user)->name ?? $s->username ?? '-' }}</td>
                            <td>{{ optional($s->user)->email ?? $s->email ?? '-' }}</td>
                            @if(auth()->user() && auth()->user()->isAdmin())
                                <td>{{ optional($s->user)->plain_password ?? '-' }}</td>
                            @endif
                            <td>
                                <div class="actions">
                                    <a class="btn-action view" href="{{ route('admin.health-staffs.show', $s) }}" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a class="btn-action edit" href="{{ route('admin.health-staffs.edit', $s) }}" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    @if(is_null($s->user_id))
                                        <form method="POST" action="{{ route('admin.health-staffs.generate', $s) }}" style="display:inline">
                                            @csrf
                                            <button class="action-btn action-regen" title="Generate Account">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.health-staffs.regenerate', $s) }}" style="display:inline">
                                            @csrf
                                            <button class="action-btn action-regen" title="Regenerate Account">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.health-staffs.destroy', $s) }}" onsubmit="return confirm('Delete this staff?')" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-delete" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if(method_exists($staff, 'links'))
                <div style="margin-top:.75rem;">{{ $staff->links() }}</div>
            @endif
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>No health staff found</p>
            </div>
        @endif
    </div>
</div>
@endsection
