<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\CaseModel;
use App\Models\Vaccination;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Get counts
        $patientsCount = Patient::count();
        $casesCount = CaseModel::count();
        $vaccinationsCount = Vaccination::count();

        // Get today's activity
        $todayRegistrations = Patient::whereDate('created_at', $today)->count();
        $todayVaccinations = Vaccination::whereDate('created_at', $today)->count();
        $todayCases = CaseModel::whereDate('created_at', $today)->count();

        // Get recent patients with their last visit
        $recentPatients = Patient::with(['owner', 'latestVisit'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'type' => $patient->type,
                    'owner_name' => $patient->owner ? $patient->owner->name : 'N/A',
                    'contact_number' => $patient->owner ? $patient->owner->contact_number : 'N/A',
                    'last_visit' => $patient->latestVisit ? $patient->latestVisit->visit_date : null,
                    'status' => $patient->status
                ];
            });

        return view('admin.dashboard', compact(
            'patientsCount',
            'casesCount',
            'vaccinationsCount',
            'todayRegistrations',
            'todayVaccinations',
            'todayCases',
            'recentPatients'
        ));
    }
}