@extends('health_staff.layout')

@section('title', 'Compose Message')

@section('content')
<style>
    .compose-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem 3rem;
        color: #111827;
        font-family: "Poppins", sans-serif;
    }

    .compose-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .compose-header h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .compose-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .compose-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.25rem;
    }

    @media (max-width: 900px) {
        .compose-grid { grid-template-columns: 1fr; }
    }

    label {
        font-weight: 600;
        display: block;
        margin-bottom: 0.4rem;
    }

    select, input[type="text"], input[type="email"], textarea {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.6rem 0.75rem;
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    select:focus, input:focus, textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }

    textarea {
        resize: vertical;
        min-height: 160px;
    }

    .aside-box {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 1rem;
    }

    .aside-box h4 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .aside-box label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        cursor: pointer;
    }

    .aside-note {
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 0.5rem;
        line-height: 1.4;
    }

    .form-section {
        margin-bottom: 1rem;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 0.55rem 1rem;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.25s, transform 0.15s;
    }

    .btn:hover { transform: translateY(-1px); }

    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: #fff;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1e40af, #1d4ed8);
    }

    .btn-ghost {
        background: #f3f4f6;
        color: #111;
    }

    .btn-ghost:hover {
        background: #e5e7eb;
    }

    .error {
        color: #dc2626;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
</style>

<div class="compose-container">
    <div class="compose-header">
        <h2><i class="bi bi-envelope-paper-fill"></i> Compose Message</h2>
        <div style="color:#6b7280; font-size:13px;">Tip: Hold <b>Ctrl/Cmd</b> to select multiple recipients</div>
    </div>

    <div class="compose-card">
        <form method="POST" action="{{ route('health_staff.messages.store') }}">
            @csrf

            <div class="compose-grid">
                <!-- Left Side -->
                <div>
                    <div class="form-section">
                        <label>Admins</label>
                        <select name="admin_ids[]" multiple size="8" id="admin-select">
                            @foreach($admins as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->username ?? $a->email }})</option>
                            @endforeach
                        </select>
                        @error('admin_ids') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-section">
                        <label>Users</label>
                        <select name="user_ids[]" multiple size="8" id="users-select">
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->username ?? $u->email }})</option>
                            @endforeach
                        </select>
                        @error('user_ids') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Right Side -->
                <aside class="aside-box">
                    <h4><i class="bi bi-broadcast-pin"></i> Broadcast Options</h4>
                    <label><input type="checkbox" name="send_to_all_admins" id="send-all-admins"> Send to all admins</label>
                    <label><input type="checkbox" name="send_to_all_users" id="send-all-users"> Send to all users</label>
                    <div class="aside-note">
                        Broadcast will ignore selected individuals and deliver to all members of that group.
                    </div>
                </aside>
            </div>

            <div class="form-section" style="margin-top:1.5rem;">
                <label>Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}">
                @error('subject') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-section">
                <label>Message</label>
                <textarea name="body" rows="8">{{ old('body') }}</textarea>
                @error('body') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:0.5rem; justify-content:flex-end; margin-top:1.25rem;">
                <a href="{{ url('/health_staff/messages') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </div>
        </form>
    </div>
</div>

<script>
    const sendAllAdmins = document.getElementById('send-all-admins');
    const sendAllUsers = document.getElementById('send-all-users');
    const adminSelect = document.getElementById('admin-select');
    const usersSelect = document.getElementById('users-select');

    function updateRecipientState() {
        adminSelect.disabled = !!sendAllAdmins.checked;
        usersSelect.disabled = !!sendAllUsers.checked;
        adminSelect.style.opacity = sendAllAdmins.checked ? "0.6" : "1";
        usersSelect.style.opacity = sendAllUsers.checked ? "0.6" : "1";
    }

    sendAllAdmins.addEventListener('change', updateRecipientState);
    sendAllUsers.addEventListener('change', updateRecipientState);

    (function preselectFromQuery(){
        const params = new URLSearchParams(window.location.search);
        const rid = params.get('recipient_id');
        if (!rid) return;
        let opt = document.querySelector('#admin-select option[value="'+rid+'"]');
        if (opt) { opt.selected = true; opt.parentElement.scrollTop = opt.offsetTop - 40; }
        else {
            opt = document.querySelector('#users-select option[value="'+rid+'"]');
            if (opt) { opt.selected = true; opt.parentElement.scrollTop = opt.offsetTop - 40; }
        }
    })();
</script>
@endsection
