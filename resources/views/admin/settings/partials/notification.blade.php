<div class="card" style="margin-bottom:1rem">
    <div class="card-header">
        <h3><i class="bi bi-bell-fill"></i> Notification Settings</h3>
        <a href="#" class="btn ghost">Configure</a>
    </div>
    <div class="muted">Control how the system sends alerts and reminders.</div>
    <div style="margin-top:.6rem">
        <div class="checkbox-row">
            <input id="notify_email_toggle" type="checkbox" {{ old('notify_email_toggle') ? 'checked' : '' }}>
            <label for="notify_email_toggle">Receive Email Alerts (new cases, vaccination updates)</label>
        </div>
        <div class="checkbox-row">
            <input id="notify_reminder_toggle" type="checkbox" {{ old('notify_reminder_toggle') ? 'checked' : '' }}>
            <label for="notify_reminder_toggle">Automatic Reminders (upcoming vaccination schedules)</label>
        </div>
        <div class="field">
            <label>SMTP Host</label>
            <input id="smtp_host" type="text" value="{{ old('smtp_host', 'smtp.example.com') }}">
        </div>
        <div style="display:flex;gap:.5rem">
            <div class="field" style="flex:1">
                <label>SMTP Port</label>
                <input id="smtp_port" type="text" value="{{ old('smtp_port', '587') }}">
            </div>
            <div class="field" style="width:15rem">
                <label>From Email</label>
                <input id="smtp_from" type="email" value="{{ old('smtp_from', 'noreply@clinic.com') }}">
            </div>
        </div>
        <div style="display:flex;gap:.5rem;margin-top:.5rem">
            <button id="save_smtp" class="btn primary">Save SMTP</button>
            <a href="#" class="btn ghost">Edit Templates</a>
        </div>
    </div>
</div>
