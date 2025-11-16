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
            // address will be composed from municipality + barangay when present
            'address' => ['nullable', 'string'],
            'municipality' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'barangay_other' => ['nullable', 'string', 'max:255'],
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
            'woundsLocation' => ['nullable', 'string', 'max:255'],
            'animalStatus' => ['nullable', 'string', 'max:50'],
        ]);

        // Create a patient record. If credentials are provided, we'll create
        // a user below and link it.
        // Build address from municipality + barangay (prefer barangay_other when 'other' selected)
        $municipality = $request->input('municipality');
        $barangayInput = $request->input('barangay');
        $barangayOther = $request->input('barangay_other');

        $barangayValue = null;
        if ($barangayInput) {
            if ($barangayInput === 'other') {
                $barangayValue = $barangayOther ? trim($barangayOther) : null;
            } else {
                $barangayValue = trim($barangayInput);
            }
        } elseif ($barangayOther) {
            $barangayValue = trim($barangayOther);
        }

        $addressParts = [];
        if ($municipality && trim($municipality) !== '') $addressParts[] = trim($municipality);
        if ($barangayValue && $barangayValue !== '') $addressParts[] = $barangayValue;
        $composedAddress = count($addressParts) ? implode(', ', $addressParts) : ($data['address'] ?? null);

        $patient = Patient::create([
            'name' => $data['fullName'],
            'dob' => $data['dob'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $composedAddress,
            'contact' => $data['contact'] ?? null,
            'email' => $data['email'] ?? null,
            'exposure_date' => $data['exposureDate'] ?? null,
            'exposure_type' => $data['exposureType'] ?? null,
            'animal' => $data['animal'] ?? null,
            'vaccination_status' => $data['vaccinationStatus'] ?? null,
            'last_dose_date' => $data['lastDoseDate'] ?? null,
            'clinic' => $data['clinic'] ?? null,
            'emergency_contact' => $data['emergencyContact'] ?? null,
            'wounds_location' => $data['woundsLocation'] ?? null,
            'animal_status' => $data['animalStatus'] ?? null,
        ]);


        // Registration does not create user credentials. Admin will generate
        // usernames and passwords and link them to patient records.

        // If this is an AJAX request return JSON so a JS frontend can handle
        // the next step. Otherwise use Post/Redirect/Get and redirect to the
        // named completion route so the success page has its own URL and the
        // browser won't resubmit the form on refresh.
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['patient' => $patient], 201);
        }

        return redirect()->route('register.complete', ['patient' => $patient->id]);
    }

    /**
     * AJAX: check if a patient with the provided name or email already exists.
     * Returns 200 with { exists: false } when not found, or 409 with details when found.
     */
    public function checkExists(Request $request)
    {
        $data = $request->validate([
            'fullName' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        $name = trim($data['fullName']);
        $email = $data['email'] ?? null;

        $matches = [];

        // case-insensitive name check
        if (\App\Models\Patient::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->exists()) {
            $matches[] = 'name';
        }

        if ($email && \App\Models\Patient::where('email', $email)->exists()) {
            $matches[] = 'email';
        }

        if (!empty($matches)) {
            $messageParts = [];
            if (in_array('name', $matches)) $messageParts[] = 'name';
            if (in_array('email', $matches)) $messageParts[] = 'email';
            $msg = 'Patient already exists with same ' . implode(' and ', $messageParts) . '.';
            return response()->json(['exists' => true, 'matches' => $matches, 'message' => $msg], 409);
        }

        return response()->json(['exists' => false], 200);
    }
}
