<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\CaseModel;

class UserCaseController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $patient = Patient::where('user_id', $user->id)->first();
        if (!$patient) {
            return redirect()->route('user.dashboard')->with('error', 'No patient record linked to your account.');
        }
        return view('user.cases-create', compact('patient'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $patient = Patient::where('user_id', $user->id)->first();
        if (!$patient) {
            return redirect()->route('user.dashboard')->with('error', 'No patient record linked to your account.');
        }

        $data = $request->validate([
            'date_reported' => 'nullable|date',
            'status' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $data['patient_id'] = $patient->id;
        $data['reported_by'] = $user->id;

        CaseModel::create($data);

        return redirect()->route('user.cases')->with('success', 'Case submitted');
    }
}
