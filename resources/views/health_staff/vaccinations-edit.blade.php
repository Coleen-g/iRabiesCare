@extends('health_staff.layout')

@section('title', 'Edit Vaccination')

@section('content')
<style>
	/* ===== Unified Styling for Health Staff Forms ===== */
	body { background-color: #f9fafb; font-family: "Inter", "Segoe UI", sans-serif; }

	.edit-card {
		max-width: 950px;
		margin: 2.5rem auto;
		background: #fff;
		border-radius: 14px;
		padding: 2rem;
		border: 1px solid #e5e7eb;
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
	}

	.edit-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 1.75rem;
	}

	.edit-title {
		display: flex;
		gap: 1rem;
		align-items: center;
	}

	.edit-icon {
		width: 3rem;
		height: 3rem;
		background: #2563eb;
		color: #fff;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.3rem;
		box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
	}

	.edit-header h2 { margin: 0; font-size: 1.4rem; font-weight: 600; color: #111827; }
	.edit-header small { color: #6b7280; font-size: 0.9rem; }

	.form-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 1.25rem 2rem;
	}

	.form-group { display: flex; flex-direction: column; }
	.form-group label {
		font-weight: 600;
		color: #374151;
		margin-bottom: 0.35rem;
		display: flex;
		align-items: center;
		gap: 0.5rem;
		font-size: 0.9rem;
	}

	.form-group label i { color: #2563eb; }

	.form-group input,
	.form-group select,
	.form-group textarea {
		padding: 0.7rem;
		border: 1px solid #d1d5db;
		border-radius: 8px;
		font-size: 0.95rem;
		background: #f9fafb;
		transition: all 0.2s ease;
	}

	.form-group input:focus,
	.form-group select:focus,
	.form-group textarea:focus {
		border-color: #2563eb;
		background: #fff;
		outline: none;
		box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
	}

	.form-group textarea { min-height: 120px; resize: vertical; }

	.form-actions {
		display: flex;
		gap: 1rem;
		justify-content: flex-end;
		margin-top: 1.75rem;
	}

	.btn-primary {
		background: linear-gradient(90deg, #2563eb, #1e40af);
		color: #fff;
		padding: 0.75rem 1.4rem;
		border-radius: 8px;
		border: none;
		font-weight: 600;
		cursor: pointer;
		display: flex;
		align-items: center;
		gap: 0.5rem;
		box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
		transition: all 0.25s ease;
	}

	.btn-primary:hover {
		transform: translateY(-2px);
		box-shadow: 0 6px 14px rgba(37, 99, 235, 0.35);
	}

	.btn-secondary {
		background: #f3f4f6;
		color: #111;
		padding: 0.75rem 1.4rem;
		border-radius: 8px;
		text-decoration: none;
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: 0.5rem;
		border: 1px solid #e5e7eb;
		transition: all 0.25s ease;
	}

	.btn-secondary:hover {
		background: #e5e7eb;
	}

	@media (max-width: 800px) {
		.form-grid { grid-template-columns: 1fr; }
		.form-actions { flex-direction: column; }
	}
</style>

<div class="edit-card">
	<div class="edit-header">
		<div class="edit-title">
			<div class="edit-icon"><i class="fa-solid fa-syringe"></i></div>
			<div>
				<h2>Edit Vaccination</h2>
				<small>Update vaccination details for a patient</small>
			</div>
		</div>
		<a href="{{ route('health_staff.vaccinations.index') }}" class="btn-secondary">
			<i class="fa-solid fa-arrow-left"></i> Back to Vaccinations
		</a>
	</div>

	<form method="POST" action="{{ route('health_staff.vaccinations.update', $vaccination) }}">
		@csrf
		@method('PUT')

		<div class="form-grid">
			<div class="form-group">
				<label><i class="fa-solid fa-user"></i> Patient</label>
				<select name="patient_id" required class="searchable-patient-select">
					@foreach($patients as $pt)
						<option value="{{ $pt->id }}" {{ $pt->id == $vaccination->patient_id ? 'selected' : '' }}>
							{{ $pt->name }}
						</option>
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label><i class="fa-solid fa-calendar-day"></i> Date Given</label>
				<input name="date_given" type="date" value="{{ $vaccination->date_given }}" />
			</div>
		</div>

		<div class="form-grid">
			<div class="form-group">
				<label><i class="fa-solid fa-prescription-bottle-medical"></i> Vaccine</label>
				<input name="vaccine" value="{{ $vaccination->vaccine }}" placeholder="e.g. Rabivax" />
			</div>

			<div class="form-group">
				<label><i class="fa-solid fa-vial"></i> Dose</label>
				<input name="dose" value="{{ $vaccination->dose }}" placeholder="e.g. 0.5 mL" />
			</div>
		</div>

		<div class="form-grid">
			<div class="form-group" style="grid-column: 1 / -1;">
				<label><i class="fa-solid fa-user-nurse"></i> Administered By</label>
				<input name="administered_by" value="{{ $vaccination->administered_by }}" placeholder="e.g. Dr. Smith" />
			</div>

			<div class="form-group" style="grid-column: 1 / -1;">
				<label><i class="fa-solid fa-notes-medical"></i> Remarks</label>
				<textarea name="remarks" placeholder="Additional remarks...">{{ $vaccination->remarks ?? '' }}</textarea>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn-primary">
				<i class="fa-solid fa-floppy-disk"></i> Update Vaccination
			</button>
			<a href="{{ route('health_staff.vaccinations.index') }}" class="btn-secondary">
				<i class="fa-solid fa-xmark"></i> Cancel
			</a>
		</div>
	</form>
</div>
@endsection
