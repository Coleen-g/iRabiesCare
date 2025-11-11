<div class="card" style="margin-bottom:1rem">
    <div class="card-header">
        <h3><i class="bi bi-syringe"></i> Vaccination Settings</h3>
        <a href="#" class="btn ghost">Manage</a>
    </div>
    <div class="muted">Configure default vaccine settings and follow-up schedule rules.</div>
    <div style="margin-top:.6rem">
        <div class="field">
            <label>Default Vaccine Name</label>
            <input id="vaccine_default" type="text" value="{{ old('vaccine_default', 'Verorab') }}">
        </div>
        <div class="field">
            <label>Dose Intervals (days)</label>
            <div style="display:flex;gap:.5rem;flex-wrap:wrap">
                <input id="dose_day_0" type="text" value="{{ old('dose_day_0', '0') }}" style="width:6rem">
                <input id="dose_day_3" type="text" value="{{ old('dose_day_3', '3') }}" style="width:6rem">
                <input id="dose_day_7" type="text" value="{{ old('dose_day_7', '7') }}" style="width:6rem">
                <input id="dose_day_14" type="text" value="{{ old('dose_day_14', '14') }}" style="width:6rem">
                <input id="dose_day_30" type="text" value="{{ old('dose_day_30', '30') }}" style="width:6rem">
            </div>
            <div class="small-note" style="margin-top:.4rem">Specify the standard schedule used when generating patient vaccination reminders.</div>
        </div>
        <div class="field" style="display:flex;align-items:center;gap:.6rem">
            <input id="auto_generate_next" type="checkbox" {{ old('auto_generate_next') ? 'checked' : '' }}>
            <label style="margin:0">Auto-generate next schedule for patients (when a dose is recorded)</label>
        </div>
    </div>
</div>
