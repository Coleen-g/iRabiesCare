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
            'schedule_1_status' => 'nullable|string|in:pending,missed,completed',
            'schedule_2_status' => 'nullable|string|in:pending,missed,completed',
            'schedule_3_status' => 'nullable|string|in:pending,missed,completed',
            'schedule_1_remarks' => 'nullable|string',
            'schedule_2_remarks' => 'nullable|string',
            'schedule_3_remarks' => 'nullable|string',
            'overall_remarks' => 'nullable|string|in:Incomplete,Completed',
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
                'schedule_1_status' => $request->schedule_1_status ?? ($request->old('schedule_1_status') ?? 'pending'),
                'schedule_2_status' => $request->schedule_2_status ?? ($request->old('schedule_2_status') ?? 'pending'),
                'schedule_3_status' => $request->schedule_3_status ?? ($request->old('schedule_3_status') ?? 'pending'),
                'schedule_1_remarks' => $request->schedule_1_remarks,
                'schedule_2_remarks' => $request->schedule_2_remarks,
                'schedule_3_remarks' => $request->schedule_3_remarks,
                'overall_remarks' => $request->overall_remarks ?? null,
            ]
        );

        // Notify patient user that schedule changed
        if ($user && $user->patient) {
            $user->notify(new ScheduleChangedNotification($schedule));
        }

        // If AJAX, return JSON so client can update UI without redirect
        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Vaccination schedule updated',
                'schedule' => $schedule->fresh(),
            ]);
        }

        return redirect()->route('health_staff.vaccinations.index')
            ->with('success', 'Vaccination schedule updated!');
    }
}
