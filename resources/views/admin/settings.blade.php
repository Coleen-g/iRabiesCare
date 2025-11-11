@extends('admin.layout')

@section('title', 'Settings')

@section('content')
<style>
	:root {
		--primary: #111827;
		--accent: #2563eb;
		--border: #e5e7eb;
		--bg: #f9fafb;
	}

	body { background: var(--bg); font-family: "Inter", "Poppins", sans-serif; }

	.settings-container {
		padding: 2rem 1.5rem;
		max-width: 1300px;
		margin: 0 auto;
	}

	.settings-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 1rem;
		margin-bottom: 2rem;
	}

	.settings-title {
		display: flex;
		align-items: center;
		gap: 1rem;
	}

	.settings-title .icon {
		width: 3.2rem;
		height: 3.2rem;
		border-radius: 14px;
		background: linear-gradient(135deg, var(--accent), #1e3a8a);
		color: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.25rem;
		box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
	}

	h1.page {
		margin: 0;
		font-size: 1.6rem;
		font-weight: 700;
		color: var(--primary);
	}

	.small-note {
		color: #6b7280;
		font-size: 0.9rem;
	}

	.btn {
		padding: 0.6rem 1.2rem;
		border-radius: 8px;
		font-weight: 600;
		cursor: pointer;
		border: none;
		transition: all 0.25s ease;
	}

	.btn.primary {
		background: linear-gradient(90deg, var(--accent), #1e40af);
		color: #fff;
		box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
	}

	.btn.primary:hover {
		transform: translateY(-2px);
		box-shadow: 0 6px 14px rgba(37, 99, 235, 0.35);
	}

	.btn.ghost {
		background: #f3f4f6;
		color: #111;
		border: 1px solid var(--border);
	}

	.btn.ghost:hover {
		background: #e5e7eb;
	}

	.grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 1.5rem;
	}

	@media(max-width:1000px) {
		.grid { grid-template-columns: 1fr; }
	}

	.card {
		background: #fff;
		border-radius: 14px;
		padding: 1.5rem;
		border: 1px solid var(--border);
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
		transition: all 0.25s ease;
	}

	.card:hover {
		transform: translateY(-2px);
		box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
	}

	.card-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 1rem;
	}

	.card h3 {
		margin: 0;
		font-size: 1rem;
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: 0.5rem;
		color: var(--primary);
	}

	.muted {
		color: #6b7280;
		font-size: 0.9rem;
		margin-bottom: 0.75rem;
	}

	.field {
		margin-bottom: 0.8rem;
	}

	.field label {
		display: block;
		font-weight: 600;
		margin-bottom: 0.3rem;
		color: var(--primary);
		font-size: 0.95rem;
	}

	.field input,
	.field select,
	.field textarea {
		width: 100%;
		padding: 0.55rem 0.75rem;
		border-radius: 8px;
		border: 1px solid #d1d5db;
		background: #f9fafb;
		font-size: 0.95rem;
		transition: all 0.25s ease;
	}

	.field input:focus,
	.field select:focus,
	.field textarea:focus {
		border-color: var(--accent);
		outline: none;
		background: #fff;
		box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
	}

	.checkbox-row {
		display: flex;
		align-items: center;
		gap: 0.5rem;
		margin-bottom: 0.6rem;
	}

	.small-note {
		font-size: 0.85rem;
		color: #6b7280;
	}
</style>

<div class="settings-container">
	<div class="settings-header">
		<div class="settings-title">
			<div class="icon"><i class="bi bi-gear-fill"></i></div>
			<div>
				<h1 class="page">System Settings</h1>
				<div class="small-note">Manage general, facility, vaccination, and notification configurations</div>
			</div>
		</div>
		<button id="save_settings" class="btn primary">
			<i class="bi bi-save2 me-1"></i> Save Changes
		</button>
	</div>

	<div class="grid">
		<!-- LEFT COLUMN -->
		<div>
			@include('admin.settings.partials.profile')
			@include('admin.settings.partials.facility')
			@include('admin.settings.partials.vaccine')
		</div>

		<!-- RIGHT COLUMN -->
		<div>
			@include('admin.settings.partials.location')
			@include('admin.settings.partials.notification')
			@include('admin.settings.partials.users')
			@include('admin.settings.partials.appearance')
			@include('admin.settings.partials.security')
		</div>
	</div>
</div>
@endsection
