<div class="card" style="margin-bottom:1rem">
    <div class="card-header">
        <h3><i class="bi bi-building"></i> Facility / System Information</h3>
        <a href="#" class="btn ghost">Manage</a>
    </div>
    <div class="muted">Details about the health center or hospital used for templates and notifications.</div>
    <div style="margin-top:.6rem">
        <div class="field">
            <label>Health Center / Hospital Name</label>
            <input id="facility_name" type="text" value="{{ old('facility_name', 'Barangay Health Center') }}">
        </div>
        <div class="field">
            <label>Address</label>
            <input id="facility_address" type="text" value="{{ old('facility_address', '') }}">
        </div>
        <div style="display:flex;gap:.5rem">
            <div style="flex:1" class="field">
                <label>Municipality / Province</label>
                <input id="facility_municipality" type="text" value="{{ old('facility_municipality', '') }}">
            </div>
            <div style="width:12rem" class="field">
                <label>Hotline Number</label>
                <input id="facility_hotline" type="text" value="{{ old('facility_hotline', '') }}">
            </div>
        </div>
        <div class="field">
            <label>Email for Notifications</label>
            <input id="facility_notify_email" type="email" value="{{ old('facility_notify_email', '') }}">
        </div>
    </div>
</div>
