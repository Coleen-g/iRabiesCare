<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $q = request('q');

        $patients = Patient::when($q, function($query, $q) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('contact', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
        })
        // Exclude patients that are actually admin or health_staff users (if linked via user_id)
        ->whereDoesntHave('user', function($query) {
            $query->whereIn('role', ['admin', 'health_staff']);
        })
        ->latest()->paginate(15)->withQueryString();
        return view('admin.patients', compact('patients'));
    }

    /**
     * AJAX autocomplete for patients
     */
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $results = Patient::when($q, function($query, $q) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('contact', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
        })
        ->whereDoesntHave('user', function($query) {
            $query->whereIn('role', ['admin', 'health_staff']);
        })
        ->orderBy('name')->limit(20)->get();

        // choices.js expects items with value & label/text; we'll return id and a combined label
        $payload = $results->map(function($p){
            return ['value' => $p->id, 'label' => $p->name . ($p->contact ? ' — '. $p->contact : '')];
        });

        return response()->json($payload);
    }

    public function create()
    {
        return view('admin.patients-create');
    }

    public function store(Request $request)
    {
        \Log::info('PatientController@store called', ['user_id' => optional(auth()->user())->id, 'payload' => $request->all()]);
        $validated = $request->validate([
            // allow either admin form's 'name' or registration's 'fullName'
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
            'wounds_location' => 'nullable|string|max:255',
            'animal_status' => 'nullable|string|in:Alive,Dead,Unknown',
            'vaccination_status' => 'nullable|string|max:50',
            'vaccinationStatus' => 'nullable|string|max:50',
            'last_dose' => 'nullable|date',
            'lastDoseDate' => 'nullable|date',
            'status' => 'nullable|string|in:Active,Pending,Completed',
        ]);

        // Normalize incoming fields (support both admin and public registration field names)
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
            'wounds_location' => $validated['wounds_location'] ?? null,
            'animal_status' => $validated['animal_status'] ?? null,
            'vaccination_status' => $validated['vaccination_status'] ?? $validated['vaccinationStatus'] ?? null,
            'last_dose_date' => $validated['last_dose'] ?? $validated['lastDoseDate'] ?? null,
            'status' => $validated['status'] ?? 'Active',
        ];

        try {
            $patient = Patient::create($payload);
        } catch (\Exception $e) {
            \Log::error('Failed to create Patient', ['error' => $e->getMessage(), 'payload' => $payload]);
            return back()->withInput()->withErrors(['error' => 'Failed to create patient, check logs.']);
        }

        // Mirror RegisterController behavior: return JSON for AJAX/JS clients
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['patient' => $patient], 201);
        }

        return redirect()->route('admin.patients.show', $patient)->with('success', 'Patient created');
    }

    public function edit(Patient $patient)
    {
        // load assigned health staff relation and provide list of available health_staff users
        $patient->load('assignedHealthStaff');
        $healthStaff = User::where('role', 'health_staff')->orderBy('name')->get();
        return view('admin.patients-edit', compact('patient', 'healthStaff'));
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
            'wounds_location' => 'nullable|string|max:255',
            'animal_status' => 'nullable|string|in:Alive,Dead,Unknown',
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
            'wounds_location' => $validated['wounds_location'] ?? $patient->wounds_location,
            'animal_status' => $validated['animal_status'] ?? $patient->animal_status,
            'vaccination_status' => $validated['vaccination_status'] ?? $validated['vaccinationStatus'] ?? $patient->vaccination_status,
            'last_dose_date' => $validated['last_dose'] ?? $validated['lastDoseDate'] ?? $patient->last_dose_date,
            'status' => $validated['status'] ?? $patient->status,
        ];

        $patient->update($payload);

        // Sync assigned health staff if provided (accept a single id or an array)
        if ($request->has('assigned_health_staff')) {
            $input = $request->input('assigned_health_staff', null);
            $assigned = [];
            if (is_array($input)) {
                $assigned = collect($input)->map(function($v){ return (int) $v; })->filter()->unique()->values()->all();
            } elseif ($input) {
                $assigned = [(int) $input];
            }

            // preserve existing pivot assigned_by/assigned_at when present
            $existing = $patient->assignedHealthStaff()->get()->keyBy('id')->map(function($u){
                return [
                    'assigned_by' => $u->pivot->assigned_by ?? null,
                    'assigned_at' => $u->pivot->assigned_at ?? null,
                ];
            })->toArray();

            $syncData = [];
            foreach ($assigned as $id) {
                if (isset($existing[$id]) && $existing[$id]['assigned_by']) {
                    $syncData[$id] = [
                        'assigned_by' => $existing[$id]['assigned_by'],
                        'assigned_at' => $existing[$id]['assigned_at'],
                    ];
                } else {
                    $syncData[$id] = [
                        'assigned_by' => auth()->id(),
                        'assigned_at' => now(),
                    ];
                }
            }

            $patient->assignedHealthStaff()->sync($syncData);
        }

        return redirect()->route('admin.patients.show', $patient)->with('success', 'Patient updated');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('admin.patients.index')->with('success', 'Patient deleted');
    }

    public function show(Patient $patient)
    {
        // return a simple patient detail view; create view if missing
        return view('admin.patients-show', compact('patient'));
    }
}
