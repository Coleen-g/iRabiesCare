<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vaccination;
use App\Models\Patient;
use App\Notifications\VaccinationRecordedNotification;
use App\Notifications\VaccinationCompletedNotification;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    public function index()
    {
        $q = request('q');

        $vaccinations = Vaccination::with(['patient.user.vaccinationSchedule'])
            ->when($q, function($query, $q) {
                $query->where('vaccine', 'like', "%{$q}%")
                      ->orWhere('notes', 'like', "%{$q}%")
                      ->orWhereHas('patient', function($q2) use ($q) {
                          $q2->where('name', 'like', "%{$q}%");
                      });
            })
            ->latest('date_given')
            ->paginate(15)
            ->withQueryString();
        
        return view('admin.vaccinations', compact('vaccinations'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        return view('admin.vaccinations-create', compact('patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_given' => 'nullable|date',
            'vaccine' => 'nullable|string|max:255',
            'dose' => 'nullable|string|max:100',
            'administered_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $vaccination = Vaccination::create($data);

        // Notify patient user (if exists)
        $patient = Patient::find($data['patient_id']);
        if ($patient && $patient->user) {
            $patient->user->notify(new VaccinationRecordedNotification($vaccination));

            // Check for course completion: compare scheduled dates count vs vaccination count
            $schedule = optional($patient->user->vaccinationSchedule);
            if ($schedule) {
                $dates = array_filter([
                    $schedule->schedule_1,
                    $schedule->schedule_2,
                    $schedule->schedule_3,
                ]);
                $scheduledCount = count($dates);
                if ($scheduledCount > 0) {
                    $vaccinationCount = Vaccination::where('patient_id', $patient->id)->count();
                    $allPast = collect($dates)->every(function($d) { return Carbon::parse($d)->startOfDay()->lte(Carbon::today()); });
                    if ($allPast && $vaccinationCount >= $scheduledCount) {
                        // send completed notification if not already sent
                        $already = $patient->user->notifications()->where('type', VaccinationCompletedNotification::class)->exists();
                        if (! $already) {
                            $last = Vaccination::where('patient_id', $patient->id)->latest('date_given')->first();
                            $patient->user->notify(new VaccinationCompletedNotification($last ? $last->date_given : null));
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.vaccinations.index')->with('success', 'Vaccination recorded');
    }

    public function edit(Vaccination $vaccination)
    {
        $patients = Patient::orderBy('name')->get();
        return view('admin.vaccinations-edit', compact('vaccination', 'patients'));
    }

    public function update(Request $request, Vaccination $vaccination)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_given' => 'nullable|date',
            'vaccine' => 'nullable|string|max:255',
            'dose' => 'nullable|string|max:100',
            'administered_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $vaccination->update($data);

        // Notify patient user (if exists)
        $patient = $vaccination->patient;
        if ($patient && $patient->user) {
            $patient->user->notify(new VaccinationRecordedNotification($vaccination));

            // Check completion similarly
            $schedule = optional($patient->user->vaccinationSchedule);
            if ($schedule) {
                $dates = array_filter([
                    $schedule->schedule_1,
                    $schedule->schedule_2,
                    $schedule->schedule_3,
                ]);
                $scheduledCount = count($dates);
                if ($scheduledCount > 0) {
                    $vaccinationCount = Vaccination::where('patient_id', $patient->id)->count();
                    $allPast = collect($dates)->every(function($d) { return Carbon::parse($d)->startOfDay()->lte(Carbon::today()); });
                    if ($allPast && $vaccinationCount >= $scheduledCount) {
                        $already = $patient->user->notifications()->where('type', VaccinationCompletedNotification::class)->exists();
                        if (! $already) {
                            $last = Vaccination::where('patient_id', $patient->id)->latest('date_given')->first();
                            $patient->user->notify(new VaccinationCompletedNotification($last ? $last->date_given : null));
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.vaccinations.index')->with('success', 'Vaccination updated');
    }

    public function destroy(Vaccination $vaccination)
    {
        $vaccination->delete();
        return redirect()->route('admin.vaccinations.index')->with('success', 'Vaccination deleted');
    }
}
