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
}
