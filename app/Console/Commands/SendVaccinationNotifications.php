<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VaccinationSchedule;
use App\Models\Vaccination;
use App\Notifications\UpcomingVaccinationReminder;
use App\Notifications\OverdueVaccinationReminder;
use App\Notifications\VaccinationCompletedNotification;
use Carbon\Carbon;

class SendVaccinationNotifications extends Command
{
    protected $signature = 'send:vaccination-notifications';
    protected $description = 'Send upcoming, overdue and completion vaccination notifications to patients based on their vaccination schedules.';

    public function handle()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $schedules = VaccinationSchedule::with('user.patient')->get();

        foreach ($schedules as $s) {
            $user = $s->user;
            if (! $user) continue;

            $patient = $user->patient;
            if (! $patient) continue;

            // Gather schedule dates
            $slots = [
                'schedule_1' => $s->schedule_1,
                'schedule_2' => $s->schedule_2,
                'schedule_3' => $s->schedule_3,
            ];

            // Remove nulls and normalize to dates
            $dates = [];
            foreach ($slots as $key => $dt) {
                if (empty($dt)) continue;
                try { $d = Carbon::parse($dt)->startOfDay(); } catch (\Throwable $e) { continue; }
                $dates[$key] = $d;
            }

            // Process each scheduled date
            foreach ($dates as $slotKey => $d) {
                // Has a vaccination recorded on that date for this patient?
                $hasVaccination = Vaccination::where('patient_id', $patient->id)
                    ->whereDate('date_given', $d->toDateString())->exists();

                // Avoid duplicate identical notifications in the same day
                $alreadyNotifiedUpcoming = $user->notifications()
                    ->where('type', UpcomingVaccinationReminder::class)
                    ->where('data->schedule_date', $d->toDateString())
                    ->whereDate('created_at', $today->toDateString())
                    ->exists();

                $alreadyNotifiedOverdue = $user->notifications()
                    ->where('type', OverdueVaccinationReminder::class)
                    ->where('data->schedule_date', $d->toDateString())
                    ->whereDate('created_at', $today->toDateString())
                    ->exists();

                if (! $hasVaccination) {
                    // Upcoming: send if date is today or tomorrow
                    if (($d->equalTo($today) || $d->equalTo($tomorrow)) && ! $alreadyNotifiedUpcoming) {
                        $user->notify(new \App\Notifications\UpcomingVaccinationReminder($d->toDateString(), $slotKey));
                    }

                    // Overdue: send if date is before today
                    if ($d->lt($today) && ! $alreadyNotifiedOverdue) {
                        $user->notify(new \App\Notifications\OverdueVaccinationReminder($d->toDateString(), $slotKey));
                    }
                }
            }

            // Completion: if all scheduled dates are in the past and there are at least as many vaccination records as dates
            $scheduledDateCount = count($dates);
            if ($scheduledDateCount > 0) {
                $allDatesPast = collect($dates)->every(function($dd) use ($today) { return $dd->lte($today); });
                $vaccinationCount = Vaccination::where('patient_id', $patient->id)->count();

                $alreadyNotifiedCompleted = $user->notifications()
                    ->where('type', VaccinationCompletedNotification::class)
                    ->exists();

                if ($allDatesPast && $vaccinationCount >= $scheduledDateCount && ! $alreadyNotifiedCompleted) {
                    $lastGiven = Vaccination::where('patient_id', $patient->id)->latest('date_given')->first();
                    $user->notify(new VaccinationCompletedNotification($lastGiven ? $lastGiven->date_given : null));
                }
            }
        }

        $this->info('Vaccination notifications processed.');
        return 0;
    }
}
