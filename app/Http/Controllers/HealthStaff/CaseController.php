<?php

namespace App\Http\Controllers\HealthStaff;

use App\Http\Controllers\Admin\CaseController as AdminCaseController;
use App\Models\CaseModel;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseController extends AdminCaseController
{
    public function index()
    {
        $q = request('q');
        $userId = optional(auth()->user())->id;

        // Only show cases for patients assigned to this health staff
        $cases = CaseModel::with(['patient', 'reporter'])
            ->whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
                $q->where('users.id', $userId);
            })
            ->when($q, function($query, $q) {
                $query->where('status', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%")
                      ->orWhereHas('patient', function($q2) use ($q) {
                          $q2->where('name', 'like', "%{$q}%");
                      });
            })
            ->latest()->paginate(15)->withQueryString();

        return view('health_staff.cases', compact('cases'));
    }

    public function create()
    {
        // limit patient selection to patients assigned to this health staff
        $userId = optional(auth()->user())->id;
        $patients = Patient::whereHas('assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->orderBy('name')->get();

        return view('health_staff.cases-create', compact('patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_reported' => 'nullable|date',
            'status' => 'required|string|max:50',
            'description' => 'nullable|string',
            'exposure_date' => 'nullable|date',
            'exposure_type' => 'nullable|string|max:255',
            'animal_species' => 'nullable|string|max:255',
        ]);

        $data['reported_by'] = Auth::id();

        // Try to copy exposure/animal from patient when not provided
        if (!empty($data['patient_id'])) {
            $patient = Patient::find($data['patient_id']);
            if ($patient) {
                $data['exposure_date'] = $data['exposure_date'] ?? ($patient->exposure_date ?? null);
                $data['exposure_type'] = $data['exposure_type'] ?? ($patient->exposure_type ?? null);
                $data['animal_species'] = $data['animal_species'] ?? ($patient->animal ?? null);
            }
        }

        CaseModel::create($data);

        return redirect()->route('health_staff.cases.index')->with('success', 'Case created');
    }

    public function edit(CaseModel $case)
    {
        $userId = optional(auth()->user())->id;

        // Ensure this health staff is assigned to the case's patient
        if ($case->patient && ! $case->patient->assignedHealthStaff()->where('users.id', $userId)->exists()) {
            abort(403, 'You are not assigned to this patient.');
        }

        // limit patient selection to patients assigned to this health staff
        $patients = Patient::whereHas('assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->orderBy('name')->get();

        return view('health_staff.cases-edit', compact('case', 'patients'));
    }

    public function update(Request $request, CaseModel $case)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_reported' => 'nullable|date',
            'status' => 'required|string|max:50',
            'description' => 'nullable|string',
            'exposure_date' => 'nullable|date',
            'exposure_type' => 'nullable|string|max:255',
            'animal_species' => 'nullable|string|max:255',
        ]);

        // If missing, copy exposure/animal from patient
        if (!empty($data['patient_id'])) {
            $patient = Patient::find($data['patient_id']);
            if ($patient) {
                $data['exposure_date'] = $data['exposure_date'] ?? ($patient->exposure_date ?? null);
                $data['exposure_type'] = $data['exposure_type'] ?? ($patient->exposure_type ?? null);
                $data['animal_species'] = $data['animal_species'] ?? ($patient->animal ?? null);
            }
        }

        $case->update($data);

        return redirect()->route('health_staff.cases.index')->with('success', 'Case updated');
    }

    public function destroy(CaseModel $case)
    {
        $case->delete();
        return redirect()->route('health_staff.cases.index')->with('success', 'Case deleted');
    }
}
