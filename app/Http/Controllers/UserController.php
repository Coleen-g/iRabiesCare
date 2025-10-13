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
        $patient = Patient::where('user_id', $user->id)->first();
        $cases = [];
        $vaccinations = [];
        if ($patient) {
            $cases = $patient->cases()->latest()->get();
            $vaccinations = $patient->vaccinations()->latest()->get();
        }

        return view('user.dashboard', compact('patient','cases','vaccinations'));
    }

    public function cases()
    {
        $user = Auth::user();
        $patient = Patient::where('user_id', $user->id)->first();
        $cases = $patient ? $patient->cases()->latest()->get() : collect();
        return view('user.cases', compact('cases'));
    }

    public function vaccinations()
    {
        $user = Auth::user();
        $patient = Patient::where('user_id', $user->id)->first();
        $vaccinations = $patient ? $patient->vaccinations()->latest()->get() : collect();
        return view('user.vaccinations', compact('vaccinations'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }
}
