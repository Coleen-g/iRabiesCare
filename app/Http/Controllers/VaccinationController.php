<?php

namespace App\Http\Controllers;

use App\Models\Vaccination;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    public function index()
    {
        return response()->json(Vaccination::with('patient')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'vaccine_type' => 'required|string|max:255',
            'dose_number' => 'required|integer',
            'vaccination_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $vaccination = Vaccination::create($validated);
        return response()->json($vaccination->load('patient'), 201);
    }

    public function destroy($id)
    {
        $vaccination = Vaccination::findOrFail($id);
        $vaccination->delete();
        return response()->json(['message' => 'Vaccination deleted'], 200);
    }
}
