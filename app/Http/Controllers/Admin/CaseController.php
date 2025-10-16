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
        ]);

        $data['reported_by'] = Auth::id();

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
        ]);

        $case->update($data);

        return redirect()->route('admin.cases.index')->with('success', 'Case updated');
    }

    public function destroy(CaseModel $case)
    {
        $case->delete();
        return redirect()->route('admin.cases.index')->with('success', 'Case deleted');
    }
}
