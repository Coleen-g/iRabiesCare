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
        $cases = CaseModel::with('patient')->latest()->paginate(15);
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
