@extends('admin.layout')

@section('content')
    <div class="container">
        <h2 class="text-2xl mb-4">Compose Message</h2>

        <form method="POST" action="{{ route('admin.messages.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block">Recipients</label>
                <div style="display:flex; gap:1rem; align-items:flex-start;">
                    <div style="flex:1; min-width:320px;">
                        <label style="font-weight:600; display:block; margin-bottom:.25rem;">Health staff</label>
                        <select name="health_staff_ids[]" class="w-full" multiple size="8" id="health-staff-select">
                        <div class="container">
                            <div class="card">
                                <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:0.5rem;">
                                    <h2 style="margin:0; font-size:1.25rem;">Compose Message</h2>
                                    <div style="color:#6b7280; font-size:13px">Tip: press Ctrl/Cmd to multi-select recipients</div>
                                </div>

                                <form method="POST" action="{{ route('admin.messages.store') }}">
                                    @csrf

                                    <div style="display:grid; grid-template-columns: 1fr 340px; gap:1rem; align-items:start;">
                                        <div>
                                            <div style="margin-bottom:0.75rem;">
                                                <label style="display:block; font-weight:700; margin-bottom:0.5rem;">Health staff</label>
                                                <select name="health_staff_ids[]" class="w-full" multiple size="8" id="health-staff-select" style="border:1px solid #e6e6e6; border-radius:8px; padding:6px; background:#fff">
                                                    @foreach($healthStaff as $staff)
                                                        <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->username ?? $staff->email }})</option>
                                                    @endforeach
                                                </select>
                                                @error('health_staff_ids') <div class="text-red-600">{{ $message }}</div> @enderror
                                            </div>

                                            <div style="margin-bottom:0.75rem;">
                                                <label style="display:block; font-weight:700; margin-bottom:0.5rem;">Users</label>
                                                <select name="user_ids[]" class="w-full" multiple size="8" id="users-select" style="border:1px solid #e6e6e6; border-radius:8px; padding:6px; background:#fff">
                                                    @foreach($users as $u)
                                                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->username ?? $u->email }})</option>
                                                    @endforeach
                                                </select>
                                                @error('user_ids') <div class="text-red-600">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <aside style="background:#fafafa; border:1px solid #f1f1f1; padding:12px; border-radius:8px;">
                                            <div style="font-weight:700; margin-bottom:8px">Broadcast</div>
                                            <div style="margin-bottom:8px;"><label><input type="checkbox" name="send_to_all_health_staff" id="send-all-staff"> <span style="margin-left:6px">Send to all health staff</span></label></div>
                                            <div style="margin-bottom:8px;"><label><input type="checkbox" name="send_to_all_users" id="send-all-users"> <span style="margin-left:6px">Send to all users</span></label></div>
                                            <div style="font-size:12px;color:#6b7280">Broadcast sends to entire groups. Selected individual recipients will be ignored when broadcasting.</div>
                                        </aside>
                                    </div>

                                    <div style="margin-top:1rem">
                                        <label style="display:block; font-weight:700; margin-bottom:0.5rem;">Subject</label>
                                        <input type="text" name="subject" class="w-full" value="{{ old('subject') }}" style="padding:10px; border:1px solid #e6e6e6; border-radius:8px;">
                                        @error('subject') <div class="text-red-600">{{ $message }}</div> @enderror
                                    </div>

                                    <div style="margin-top:1rem">
                                        <label style="display:block; font-weight:700; margin-bottom:0.5rem;">Message</label>
                                        <textarea name="body" rows="8" class="w-full" style="padding:10px; border:1px solid #e6e6e6; border-radius:8px; background:#fff">{{ old('body') }}</textarea>
                                        @error('body') <div class="text-red-600">{{ $message }}</div> @enderror
                                    </div>

                                    <div style="display:flex; gap:0.5rem; justify-content:flex-end; margin-top:1rem">
                                        <a href="{{ route('admin.messages.index') }}" class="btn-ghost">Cancel</a>
                                        <button class="btn btn-primary" type="submit">Send message</button>
                                    </div>
                                </form>
                            </div>

                            <script>
                                // Toggle disable of recipient selects when broadcast boxes are checked
                                const sendAllStaff = document.getElementById('send-all-staff');
                                const sendAllUsers = document.getElementById('send-all-users');
                                const healthSelect = document.getElementById('health-staff-select');
                                const usersSelect = document.getElementById('users-select');

                                function updateRecipientState() {
                                    healthSelect.disabled = !!sendAllStaff.checked;
                                    usersSelect.disabled = !!sendAllUsers.checked;
                                }

                                sendAllStaff.addEventListener('change', updateRecipientState);
                                sendAllUsers.addEventListener('change', updateRecipientState);

                                // Pre-select recipient when admin clicked 'Compose' from message view (query ?recipient_id=)
                                (function preselectFromQuery(){
                                    try {
                                        const params = new URLSearchParams(window.location.search);
                                        const rid = params.get('recipient_id');
                                        if (!rid) return;

                                        // attempt to select in health staff first, then users
                                        let opt = document.querySelector('#health-staff-select option[value="'+rid+'"]');
                                        if (opt) { opt.selected = true; opt.parentElement.scrollTop = opt.offsetTop - 40; }
                                        else {
                                            opt = document.querySelector('#users-select option[value="'+rid+'"]');
                                            if (opt) { opt.selected = true; opt.parentElement.scrollTop = opt.offsetTop - 40; }
                                        }
                                    } catch (e) {
                                        console && console.warn && console.warn('Preselect failed', e);
                                    }
                                })();
                            </script>
                        </div>
                    @endsection
