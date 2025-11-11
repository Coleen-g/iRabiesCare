<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\CaseModel;
use App\Models\Vaccination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        // Find patient record linked to this user (if any)
        $patient = $user->patient; // use relation
        $cases = collect();
        $vaccinations = collect();
        if ($patient) {
            // show a small recent set on the dashboard
            $cases = $patient->cases()->latest()->take(3)->get();
            $vaccinations = $patient->vaccinations()->latest()->take(3)->get();
        }

        return view('user.dashboard', compact('patient','cases','vaccinations'));
    }

    public function cases()
    {
        $user = Auth::user();
        $patient = $user->patient;
        $cases = $patient ? $patient->cases()->latest()->paginate(15) : collect();
        return view('user.cases', compact('cases'));
    }

    public function vaccinations()
    {
        $user = Auth::user();
        $patient = $user->patient;
        $vaccinations = $patient ? $patient->vaccinations()->latest()->paginate(15) : collect();
        return view('user.vaccinations', compact('vaccinations'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('user.profile-edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            // patient fields (optional)
            'contact' => 'nullable|string|max:100',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'clinic' => 'nullable|string|max:255',
        ]);

        // Update user
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        // If user has a patient record, update patient fields
        $patient = $user->patient;
        if ($patient) {
            $patient->contact = $data['contact'] ?? $patient->contact;
            $patient->dob = $data['dob'] ?? $patient->dob;
            $patient->gender = $data['gender'] ?? $patient->gender;
            $patient->address = $data['address'] ?? $patient->address;
            $patient->clinic = $data['clinic'] ?? $patient->clinic;
            $patient->save();
        }

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard')->with('success', 'Profile updated.');
        }
        if ($user->role === 'health_staff') {
            return redirect('/health/dashboard')->with('success', 'Profile updated.');
        }

        return redirect()->route('user.profile')->with('success', 'Profile updated.');
    }
}
