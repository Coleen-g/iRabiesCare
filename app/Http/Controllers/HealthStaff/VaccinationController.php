<?php

namespace App\Http\Controllers\HealthStaff;

use App\Http\Controllers\Admin\VaccinationController as AdminVaccinationController;
use App\Models\Vaccination;
use App\Models\Patient;
use App\Notifications\VaccinationRecordedNotification;
use App\Notifications\VaccinationCompletedNotification;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class VaccinationController extends AdminVaccinationController
{
    public function index()
    {
        $q = request('q');
        $userId = optional(auth()->user())->id;

        // Only include vaccinations for patients assigned to this health staff
        $vaccinations = Vaccination::with(['patient.user.vaccinationSchedule'])
            ->whereHas('patient.assignedHealthStaff', function($q) use ($userId) {
                $q->where('users.id', $userId);
            })
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

        return view('health_staff.vaccinations', compact('vaccinations'));
    }

    public function create()
    {
        $userId = optional(auth()->user())->id;
        $patients = Patient::whereHas('assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->orderBy('name')->get();

        return view('health_staff.vaccinations-create', compact('patients'));
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
        // ensure the patient is assigned to this health staff
        $userId = optional(auth()->user())->id;
        $allowed = Patient::where('id', $data['patient_id'])
            ->whereHas('assignedHealthStaff', function($q) use ($userId) {
                $q->where('users.id', $userId);
            })->exists();

        if (! $allowed) {
            abort(403, 'You are not assigned to this patient.');
        }

        $vaccination = Vaccination::create($data);

        // Notify patient user (if exists)
        $patient = Patient::find($data['patient_id']);
        if ($patient && $patient->user) {
            $patient->user->notify(new VaccinationRecordedNotification($vaccination));

            // Check for course completion
            $schedule = optional($patient->user->vaccinationSchedule);
            if ($schedule) {
                $dates = array_filter([$schedule->schedule_1, $schedule->schedule_2, $schedule->schedule_3]);
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

        return redirect()->route('health_staff.vaccinations.index')->with('success', 'Vaccination recorded');
    }

    public function edit(Vaccination $vaccination)
    {
        $userId = optional(auth()->user())->id;
        $patients = Patient::whereHas('assignedHealthStaff', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->orderBy('name')->get();

        // ensure this vaccination belongs to an assigned patient
        if (! $vaccination->patient || ! $vaccination->patient->assignedHealthStaff()->where('users.id', $userId)->exists()) {
            abort(403, 'You are not assigned to this patient.');
        }

        return view('health_staff.vaccinations-edit', compact('vaccination', 'patients'));
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
        $userId = optional(auth()->user())->id;
        $allowed = Patient::where('id', $data['patient_id'])
            ->whereHas('assignedHealthStaff', function($q) use ($userId) {
                $q->where('users.id', $userId);
            })->exists();

        if (! $allowed) {
            abort(403, 'You are not assigned to this patient.');
        }

        $vaccination->update($data);

        $patient = $vaccination->patient;
        if ($patient && $patient->user) {
            $patient->user->notify(new VaccinationRecordedNotification($vaccination));

            $schedule = optional($patient->user->vaccinationSchedule);
            if ($schedule) {
                $dates = array_filter([$schedule->schedule_1, $schedule->schedule_2, $schedule->schedule_3]);
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

        return redirect()->route('health_staff.vaccinations.index')->with('success', 'Vaccination updated');
    }

    public function destroy(Vaccination $vaccination)
    {
        $vaccination->delete();
        return redirect()->route('health_staff.vaccinations.index')->with('success', 'Vaccination deleted');
    }
}
