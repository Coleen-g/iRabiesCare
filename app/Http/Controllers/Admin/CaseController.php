<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseModel;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseController extends Controller
{
    public function index()
    {
        // eager-load patient and reporter to show full case details in the admin list
        $q = request('q');

        $cases = CaseModel::with(['patient', 'reporter'])
            ->when($q, function($query, $q) {
                $query->where('status', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%")
                      ->orWhereHas('patient', function($q2) use ($q) {
                          $q2->where('name', 'like', "%{$q}%");
                      });
            })
            ->latest()->paginate(15)->withQueryString();
        return view('admin.cases', compact('cases'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        return view('admin.cases-create', compact('patients'));
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
            'wounds_location' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'animal_species' => 'nullable|string|max:255',
            'animal_status' => 'nullable|string|max:255',
        ]);

        // prefer provided reported_by if present, otherwise use authenticated user id
        if (!$request->filled('reported_by')) {
            $data['reported_by'] = Auth::id();
        }

        // If exposure/animal fields not provided on the form, try to copy them from the selected patient
        if (!empty($data['patient_id'])) {
            $patient = Patient::find($data['patient_id']);
            if ($patient) {
                $data['exposure_date'] = $data['exposure_date'] ?? ($patient->exposure_date ?? null);
                $data['exposure_type'] = $data['exposure_type'] ?? ($patient->exposure_type ?? null);
                // patient table stores 'animal' (species) — copy into case's animal_species if missing
                $data['animal_species'] = $data['animal_species'] ?? ($patient->animal ?? null);
            }
        }

        CaseModel::create($data);

        return redirect()->route('admin.cases.index')->with('success', 'Case created');
    }

    public function edit(CaseModel $case)
    {
        $patients = Patient::orderBy('name')->get();
        return view('admin.cases-edit', compact('case', 'patients'));
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
            'wounds_location' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'animal_species' => 'nullable|string|max:255',
            'animal_status' => 'nullable|string|max:255',
        ]);

        // If exposure/animal fields not provided on the form, try to copy them from the selected patient
        if (!empty($data['patient_id'])) {
            $patient = Patient::find($data['patient_id']);
            if ($patient) {
                $data['exposure_date'] = $data['exposure_date'] ?? ($patient->exposure_date ?? null);
                $data['exposure_type'] = $data['exposure_type'] ?? ($patient->exposure_type ?? null);
                $data['animal_species'] = $data['animal_species'] ?? ($patient->animal ?? null);
            }
        }

        // Determine status transitions based on what changed.
        // Preference: if the form explicitly set 'status' and it's different, respect it.
        $original = $case->getAttributes();
        $requestedStatus = $data['status'] ?? null;

        // Fields that should trigger a status reevaluation when changed
        $triggerFields = ['wounds_location','animal_status','exposure_date','exposure_type','animal_species','category','description'];
        $anyChanged = false;
        foreach ($triggerFields as $f) {
            if (array_key_exists($f, $data)) {
                $newVal = $data[$f];
                $oldVal = array_key_exists($f, $original) ? $original[$f] : null;
                // normalize null/empty
                if ($newVal === null) $newVal = '';
                if ($oldVal === null) $oldVal = '';
                if ((string)$newVal !== (string)$oldVal) {
                    $anyChanged = true;
                    break;
                }
            }
        }

        $finalStatus = $case->status;
        if ($requestedStatus && $requestedStatus !== $case->status) {
            // explicit request to change status - use it
            $finalStatus = $requestedStatus;
        } elseif ($anyChanged) {
            // infer status from animal_status first
            if (!empty($data['animal_status'])) {
                $as = strtolower(trim($data['animal_status']));
                if (in_array($as, ['dead','died','deceased'])) {
                    $finalStatus = 'closed';
                } elseif (in_array($as, ['recovered','treated','healthy','resolved'])) {
                    $finalStatus = 'resolved';
                } else {
                    $finalStatus = 'pending';
                }
            } else {
                // other changes move the case back into an active state
                $finalStatus = 'pending';
            }
        }

        $data['status'] = $finalStatus;

        $case->update($data);

        return redirect()->route('admin.cases.index')->with('success', 'Case updated');
    }

    public function destroy(CaseModel $case)
    {
        $case->delete();
        return redirect()->route('admin.cases.index')->with('success', 'Case deleted');
    }
}
