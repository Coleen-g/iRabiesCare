<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;

class RegisterController extends Controller
{
    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        // Registration now only collects patient information. User credentials are
        // NOT created at this time. An admin will generate credentials later and
        // link the created patient record to the user account.
        $data = $request->validate([
            'fullName' => ['required', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'contact' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'exposureDate' => ['nullable', 'date'],
            'exposureType' => ['nullable', 'string', 'max:100'],
            'animal' => ['nullable', 'string', 'max:100'],
            'vaccinationStatus' => ['nullable', 'string', 'max:100'],
            'lastDoseDate' => ['nullable', 'date'],
            'clinic' => ['nullable', 'string', 'max:255'],
            'emergencyContact' => ['nullable', 'string', 'max:255'],
            // Optional credentials
            'username' => ['nullable', 'string', 'max:255', 'unique:users,name'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Create a patient record. If credentials are provided, we'll create
        // a user below and link it.
        $patient = Patient::create([
            'name' => $data['fullName'],
            'dob' => $data['dob'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'contact' => $data['contact'] ?? null,
            'email' => $data['email'] ?? null,
            'exposure_date' => $data['exposureDate'] ?? null,
            'exposure_type' => $data['exposureType'] ?? null,
            'animal' => $data['animal'] ?? null,
            'vaccination_status' => $data['vaccinationStatus'] ?? null,
            'last_dose_date' => $data['lastDoseDate'] ?? null,
            'clinic' => $data['clinic'] ?? null,
            'emergency_contact' => $data['emergencyContact'] ?? null,
        ]);


        // Registration does not create user credentials. Admin will generate
        // usernames and passwords and link them to patient records.

        // If this is an AJAX request return JSON so the React frontend can
        // redirect. Otherwise render the completion view.
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['patient' => $patient], 201);
        }

        return view('auth.register-complete', ['patient' => $patient]);
    }
}
