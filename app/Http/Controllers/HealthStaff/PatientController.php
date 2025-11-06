<?php

namespace App\Http\Controllers\HealthStaff;

use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends AdminPatientController
{
    public function index()
    {
        $q = request('q');

        // Only show patients assigned to the authenticated health staff user
        $userId = optional(auth()->user())->id;

        $patients = Patient::whereHas('assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->when($q, function($query, $q) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('contact', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
        })->latest()->paginate(15)->withQueryString();

        return view('health_staff.patients', compact('patients'));
    }

    public function create()
    {
        return view('health_staff.patients-create');
    }

    public function store(Request $request)
    {
        // reuse validation and creation logic from parent by copying core parts
        \Log::info('HealthStaff PatientController@store called', ['user_id' => optional(auth()->user())->id, 'payload' => $request->all()]);
        $validated = $request->validate([
            'name' => 'required_without:fullName|string|max:255',
            'fullName' => 'required_without:name|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'contact' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:100',
            'clinic' => 'nullable|string|max:255',
            'exposure_date' => 'nullable|date',
            'exposureDate' => 'nullable|date',
            'exposure_type' => 'nullable|string|max:255',
            'exposureType' => 'nullable|string|max:255',
            'animal' => 'nullable|string|max:255',
            'vaccination_status' => 'nullable|string|max:50',
            'vaccinationStatus' => 'nullable|string|max:50',
            'last_dose' => 'nullable|date',
            'lastDoseDate' => 'nullable|date',
            'status' => 'nullable|string|in:Active,Pending,Completed',
        ]);

        $payload = [
            'name' => $validated['name'] ?? $validated['fullName'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'contact' => $validated['contact'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'emergency_contact' => $validated['emergency_contact'] ?? $validated['emergencyContact'] ?? null,
            'clinic' => $validated['clinic'] ?? null,
            'exposure_date' => $validated['exposure_date'] ?? $validated['exposureDate'] ?? null,
            'exposure_type' => $validated['exposure_type'] ?? $validated['exposureType'] ?? null,
            'animal' => $validated['animal'] ?? null,
            'vaccination_status' => $validated['vaccination_status'] ?? $validated['vaccinationStatus'] ?? null,
            'last_dose_date' => $validated['last_dose'] ?? $validated['lastDoseDate'] ?? null,
            'status' => $validated['status'] ?? 'Active',
        ];

        try {
            $patient = Patient::create($payload);
        } catch (\Exception $e) {
            \Log::error('Failed to create Patient (health_staff)', ['error' => $e->getMessage(), 'payload' => $payload]);
            return back()->withInput()->withErrors(['error' => 'Failed to create patient, check logs.']);
        }

        // Assign the newly created patient to the creating health staff user (defense-in-depth)
        try {
            $userId = optional(auth()->user())->id;
            if ($userId && optional(auth()->user())->role === 'health_staff') {
                // avoid duplicate attach
                if (! $patient->assignedHealthStaff()->where('users.id', $userId)->exists()) {
                    $patient->assignedHealthStaff()->attach($userId, [
                        'assigned_by' => $userId,
                        'assigned_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // non-fatal: log and continue
            \Log::warning('Failed to assign patient to health_staff after create', ['error' => $e->getMessage(), 'patient_id' => $patient->id, 'user_id' => optional(auth()->user())->id]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['patient' => $patient], 201);
        }

        return redirect()->route('health_staff.patients.show', $patient)->with('success', 'Patient created');
    }

    public function edit(Patient $patient)
    {
        return view('health_staff.patients-edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required_without:fullName|string|max:255',
            'fullName' => 'required_without:name|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'contact' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:100',
            'clinic' => 'nullable|string|max:255',
            'exposure_date' => 'nullable|date',
            'exposureDate' => 'nullable|date',
            'exposure_type' => 'nullable|string|max:255',
            'exposureType' => 'nullable|string|max:255',
            'animal' => 'nullable|string|max:255',
            'vaccination_status' => 'nullable|string|max:50',
            'vaccinationStatus' => 'nullable|string|max:50',
            'last_dose' => 'nullable|date',
            'lastDoseDate' => 'nullable|date',
            'status' => 'nullable|string|in:Active,Pending,Completed',
        ]);

        $payload = [
            'name' => $validated['name'] ?? $validated['fullName'] ?? $patient->name,
            'dob' => $validated['dob'] ?? $patient->dob,
            'gender' => $validated['gender'] ?? $patient->gender,
            'contact' => $validated['contact'] ?? $patient->contact,
            'email' => $validated['email'] ?? $patient->email,
            'address' => $validated['address'] ?? $patient->address,
            'emergency_contact' => $validated['emergency_contact'] ?? $validated['emergencyContact'] ?? $patient->emergency_contact,
            'clinic' => $validated['clinic'] ?? $patient->clinic,
            'exposure_date' => $validated['exposure_date'] ?? $validated['exposureDate'] ?? $patient->exposure_date,
            'exposure_type' => $validated['exposure_type'] ?? $validated['exposureType'] ?? $patient->exposure_type,
            'animal' => $validated['animal'] ?? $patient->animal,
            'vaccination_status' => $validated['vaccination_status'] ?? $validated['vaccinationStatus'] ?? $patient->vaccination_status,
            'last_dose_date' => $validated['last_dose'] ?? $validated['lastDoseDate'] ?? $patient->last_dose_date,
            'status' => $validated['status'] ?? $patient->status,
        ];

        $patient->update($payload);

        return redirect()->route('health_staff.patients.show', $patient)->with('success', 'Patient updated');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('health_staff.patients.index')->with('success', 'Patient deleted');
    }

    public function show(Patient $patient)
    {
        // Ensure this health staff is assigned to the patient
        $user = auth()->user();
        if ($user && $user->role === 'health_staff') {
            $assigned = $patient->assignedHealthStaff()->where('users.id', $user->id)->exists();
            if (!$assigned) {
                abort(403, 'You are not assigned to this patient.');
            }
        }

        return view('health_staff.patients-show', compact('patient'));
    }
}
