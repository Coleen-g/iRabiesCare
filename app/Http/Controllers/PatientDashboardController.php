<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vaccination;
use App\Models\RabiesCase;
use Carbon\Carbon;

class PatientDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Ensure the user is a patient
        if ($user->role !== 'patient') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // --- Fetch upcoming vaccination ---
        $nextVaccination = Vaccination::where('user_id', $user->id)
            ->whereDate('date', '>=', Carbon::now())
            ->orderBy('date', 'asc')
            ->first();

        // --- Count open cases ---
        $openCasesCount = RabiesCase::where('user_id', $user->id)
            ->where('status', '!=', 'Resolved')
            ->count();

        // --- Count completed vaccinations ---
        $completedVaccinationsCount = Vaccination::where('user_id', $user->id)
            ->where('status', 'Completed')
            ->count();

        // --- Vaccination History ---
        $vaccinationHistory = Vaccination::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(10)
            ->get(['id', 'date', 'vaccine_type', 'facility_name', 'notes']);

        // --- Reported Rabies Cases ---
        $cases = RabiesCase::where('user_id', $user->id)
            ->orderBy('reported_at', 'desc')
            ->take(10)
            ->get(['id', 'reported_at', 'status', 'location']);

        // --- Build response ---
        return response()->json([
            'next_vaccination' => $nextVaccination,
            'open_cases_count' => $openCasesCount,
            'completed_vaccinations_count' => $completedVaccinationsCount,
            'vaccination_history' => $vaccinationHistory,
            'cases' => $cases,
        ]);
    }
}
