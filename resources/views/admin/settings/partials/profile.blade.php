<div class="card" style="margin-bottom:1rem">
    <div class="card-header">
        <h3><i class="bi bi-person-circle"></i> Profile Settings</h3>
        <a href="#" class="btn ghost">Edit</a>
    </div>
    <div class="muted">Update the admin profile and contact information used across the system.</div>
    <div style="margin-top:.6rem">
        <div class="field">
            <label>Name</label>
            <input id="profile_name" type="text" value="{{ old('profile_name', auth()->user()->name ?? 'Admin') }}">
        </div>
        <div class="field">
            <label>Username / Email</label>
            <input id="profile_email" type="email" value="{{ old('profile_email', auth()->user()->email ?? '') }}">
        </div>
        <div class="field">
            <label>Change Password <small class="muted">(leave blank to keep current)</small></label>
            <input id="profile_password" type="password" placeholder="New password">
        </div>
        <div class="field">
            <label>Profile Picture</label>
            <input id="profile_picture" type="file">
        </div>
        <div class="field">
            <label>Contact Number</label>
            <input id="profile_phone" type="text" value="{{ old('profile_phone', '') }}">
        </div>
    </div>
</div>
