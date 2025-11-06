<?php
namespace App\Http\Controllers\HealthStaff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VaccinationSchedule;
use App\Notifications\ScheduleChangedNotification;
use Illuminate\Http\Request;

class VaccinationScheduleController extends Controller
{
    public function edit($userId)
    {
        $user = User::findOrFail($userId);

        // Ensure this user is a patient and is assigned to the authenticated health staff
        $patient = $user->patient;
        if (! $patient || ! $patient->assignedHealthStaff()->where('users.id', auth()->id())->exists()) {
            abort(403, 'Forbidden');
        }

        $schedule = VaccinationSchedule::firstOrNew(['user_id' => $userId]);
        return view('health_staff.vaccination-schedule-edit', compact('user', 'schedule'));
    }

    public function update(Request $request, $userId)
    {
        $request->validate([
            'schedule_1' => 'nullable|date',
            'schedule_2' => 'nullable|date',
            'schedule_3' => 'nullable|date',
        ]);

        // Ensure this user corresponds to a patient assigned to this staff
        $user = User::findOrFail($userId);
        $patient = $user->patient;
        if (! $patient || ! $patient->assignedHealthStaff()->where('users.id', auth()->id())->exists()) {
            abort(403, 'Forbidden');
        }

        $schedule = VaccinationSchedule::updateOrCreate(
            ['user_id' => $userId],
            [
                'schedule_1' => $request->schedule_1,
                'schedule_2' => $request->schedule_2,
                'schedule_3' => $request->schedule_3,
            ]
        );

        // Notify patient user that schedule changed
        if ($user && $user->patient) {
            $user->notify(new ScheduleChangedNotification($schedule));
        }

        return redirect()->route('health_staff.vaccinations.index')
            ->with('success', 'Vaccination schedule updated!');
    }
}
