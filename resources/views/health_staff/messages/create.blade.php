@extends('health_staff.layout')

@section('title','Compose Message')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
        <h2 style="margin:0">Compose Message to Admin</h2>
        <div class="small-note">Send messages directly to administrator accounts.</div>
    </div>

    @if($errors->any())
        <div class="notice">{{ implode(', ', $errors->all()) }}</div>
    @endif

    <form method="POST" action="{{ route('health_staff.messages.store') }}">
        @csrf
        <div class="field">
            <label>Recipients (select one or more admins)</label>
            <div style="display:flex;gap:.5rem">
                <select name="admin_ids[]" multiple style="width:50%;padding:.5rem;border-radius:8px;border:1px solid #e6e9ee">
                    @foreach($admins as $a)
                        <option value="{{ $a->id }}">{{ $a->name }} &lt;{{ $a->email }}&gt;</option>
                    @endforeach
                </select>

                <select name="user_ids[]" multiple style="width:50%;padding:.5rem;border-radius:8px;border:1px solid #e6e9ee">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} &lt;{{ $u->email }}&gt;</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-top:.4rem;display:flex;gap:.75rem">
                <label><input type="checkbox" name="send_to_all_admins" value="1"> Send to all admins</label>
                <label><input type="checkbox" name="send_to_all_users" value="1"> Send to all users</label>
            </div>
        </div>

        <div class="field">
            <label>Subject</label>
            <input type="text" name="subject" placeholder="Optional subject" style="width:100%;padding:.5rem;border-radius:8px;border:1px solid #e6e9ee">
        </div>

        <div class="field">
            <label>Message</label>
            <textarea name="body" rows="6" style="width:100%;padding:.6rem;border-radius:8px;border:1px solid #e6e9ee" required></textarea>
        </div>

        <div style="display:flex;gap:.5rem;justify-content:flex-end">
            <a href="{{ route('health_staff.dashboard') }}" class="btn ghost">Cancel</a>
            <button type="submit" class="btn primary">Send</button>
        </div>
    </form>
</div>

@endsection
