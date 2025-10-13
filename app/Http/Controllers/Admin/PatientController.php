<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::latest()->paginate(15);
        return view('admin.patients', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients-create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'contact' => 'nullable|string|max:100',
            'address' => 'nullable|string',
        ]);

        Patient::create($data);

        return redirect()->route('admin.patients.index')->with('success', 'Patient created');
    }

    public function edit(Patient $patient)
    {
        return view('admin.patients-edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'contact' => 'nullable|string|max:100',
            'address' => 'nullable|string',
        ]);

        $patient->update($data);

        return redirect()->route('admin.patients.index')->with('success', 'Patient updated');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('admin.patients.index')->with('success', 'Patient deleted');
    }
}
