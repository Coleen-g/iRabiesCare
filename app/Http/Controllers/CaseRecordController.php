<?php

namespace App\Http\Controllers;

use App\Models\CaseRecord;
use Illuminate\Http\Request;

class CaseRecordController extends Controller
{
    public function index()
    {
        return response()->json(CaseRecord::with('patient')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'bite_date' => 'required|date',
            'bite_category' => 'required|string',
            'bite_location' => 'required|string',
            'animal_type' => 'required|string',
            'outcome' => 'required|string',
        ]);

        $case = CaseRecord::create($validated);
        return response()->json($case->load('patient'), 201);
    }

    public function destroy($id)
    {
        $case = CaseRecord::findOrFail($id);
        $case->delete();
        return response()->json(['message' => 'Case record deleted'], 200);
    }
}
