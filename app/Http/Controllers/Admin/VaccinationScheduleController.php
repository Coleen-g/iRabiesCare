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
        ]);

        $schedule = VaccinationSchedule::updateOrCreate(
            ['user_id' => $userId],
            [
                'schedule_1' => $request->schedule_1,
                'schedule_2' => $request->schedule_2,
                'schedule_3' => $request->schedule_3,
            ]
        );

        // Notify the patient user if present
        $user = User::find($userId);
        if ($user && $user->patient) {
            $user->notify(new ScheduleChangedNotification($schedule));
        }

        return redirect()->route('admin.vaccinations.index')
            ->with('success', 'Vaccination schedule updated!');
    }
}
