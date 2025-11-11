<div class="card" style="margin-bottom:1rem">
    <div class="card-header">
        <h3><i class="bi bi-palette"></i> System Appearance</h3>
        <a href="#" class="btn ghost">Preview</a>
    </div>
    <div class="muted">Theme color, dark mode and dashboard layout.</div>
    <div style="margin-top:.6rem">
        <div class="field">
            <label>Theme Color</label>
            <select id="theme_color">
                <option value="light" {{ (old('theme_color', 'light') == 'light') ? 'selected' : '' }}>Light</option>
                <option value="dark" {{ (old('theme_color', 'light') == 'dark') ? 'selected' : '' }}>Dark</option>
            </select>
        </div>
        <div class="field">
            <label>Dashboard Layout</label>
            <select id="dashboard_layout">
                <option value="full" {{ (old('dashboard_layout', 'full') == 'full') ? 'selected' : '' }}>Full View</option>
                <option value="compact" {{ (old('dashboard_layout', 'full') == 'compact') ? 'selected' : '' }}>Compact</option>
            </select>
        </div>
    </div>
</div>
