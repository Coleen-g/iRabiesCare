<div class="card">
    <div class="card-header">
        <h3><i class="bi bi-shield-lock-fill"></i> Security</h3>
        <a href="#" class="btn ghost">Audit Logs</a>
    </div>
    <div class="muted">Change session timeout, 2FA and view audit logs.</div>
    <div style="margin-top:.6rem">
        <div class="field">
            <label>Session Timeout (minutes)</label>
            <input id="session_timeout" type="text" value="{{ old('session_timeout', 30) }}">
        </div>
        <div class="small-note">Two-factor authentication and password changes are handled per user in the profile and user management screens.</div>
    </div>
</div>
