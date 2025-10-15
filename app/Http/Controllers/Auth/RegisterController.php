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
        ]);

        // Create a patient record without linking to a user account. Admin will
        // later run the credential assignment command to create users and link them.
        $patient = Patient::create([
            'name' => $data['fullName'],
            'dob' => $data['dob'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'contact' => $data['contact'] ?? null,
            // store email if provided (useful later when creating the user)
            'email' => $data['email'] ?? null,
            'exposure_date' => $data['exposureDate'] ?? null,
            'exposure_type' => $data['exposureType'] ?? null,
            'animal' => $data['animal'] ?? null,
            'vaccination_status' => $data['vaccinationStatus'] ?? null,
            'last_dose_date' => $data['lastDoseDate'] ?? null,
            'clinic' => $data['clinic'] ?? null,
            'emergency_contact' => $data['emergencyContact'] ?? null,
        ]);

        // Return a friendly page with the patient id so the user can reference it
        // later. The admin will generate login credentials and those credentials
        // will be exported to a CSV that the admin can deliver to the patient.
        return view('auth.register-complete', ['patient' => $patient]);
    }
}
