<?php
namespace App\Http\Controllers\Admin;

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
        $schedule = VaccinationSchedule::firstOrNew(['user_id' => $userId]);
        return view('admin.vaccination-schedule-edit', compact('user', 'schedule'));
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

        // Notify the patient user if present
        $user = User::find($userId);
        if ($user && $user->patient) {
            try {
                // Try to send using the notification's default channels (mail + database).
                // notifyNow is used so any exceptions are thrown here and can be handled.
                $user->notifyNow(new ScheduleChangedNotification($schedule));
            } catch (\Throwable $e) {
                // If mail delivery fails (SMTP/auth issues), ensure the in-app
                // database notification is still created and do not break the
                // user flow.
                \Log::error('Failed to send ScheduleChangedNotification: ' . $e->getMessage(), [
                    'user_id' => $userId,
                    'exception' => $e,
                ]);

                // Fallback: create database-only notification so the user still
                // sees the update in the app notifications.
                $user->notifyNow(new ScheduleChangedNotification($schedule), ['database']);
            }
        }

        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Vaccination schedule updated',
                'schedule' => $schedule->fresh(),
            ]);
        }

        return redirect()->route('admin.vaccinations.index')
            ->with('success', 'Vaccination schedule updated!');
    }

    /**
     * Return vaccination schedules for multiple users as JSON.
     * Expects POST { user_ids: [1,2,3] }
     */
    public function updates(Request $request)
    {
        $data = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|distinct|min:1',
        ]);

        $schedules = \App\Models\VaccinationSchedule::whereIn('user_id', $data['user_ids'])->get();

        $map = [];
        foreach ($schedules as $s) {
            $map[$s->user_id] = [
                'schedule_1_status' => $s->schedule_1_status,
                'schedule_2_status' => $s->schedule_2_status,
                'schedule_3_status' => $s->schedule_3_status,
                'schedule_1_remarks' => $s->schedule_1_remarks,
                'schedule_2_remarks' => $s->schedule_2_remarks,
                'schedule_3_remarks' => $s->schedule_3_remarks,
                'overall_remarks' => $s->overall_remarks,
            ];
        }

        return response()->json(['success' => true, 'schedules' => $map]);
    }
}
