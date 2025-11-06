<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ScheduleChangedNotification extends Notification
{
    use Queueable;

    protected $schedule;

    public function __construct($schedule)
    {
        $this->schedule = $schedule;
    }

    public function via($notifiable)
    {
        return $notifiable->email ? ['mail', 'database'] : ['database'];
    }

    public function toMail($notifiable)
    {
        $s = $this->schedule;
        return (new MailMessage)
                    ->subject('Vaccination Schedule Updated')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your vaccination schedule has been updated or rescheduled.')
                    ->line('Next doses:')
                    ->line('1: ' . ($s->schedule_1 ?? '-'))
                    ->line('2: ' . ($s->schedule_2 ?? '-'))
                    ->line('3: ' . ($s->schedule_3 ?? '-'))
                    ->line('If this is incorrect, please contact your health staff to reschedule.');
    }

    public function toArray($notifiable)
    {
        $s = $this->schedule;
        $message = 'First: ' . ($s->schedule_1 ?? '-') . '; Second: ' . ($s->schedule_2 ?? '-') . '; Third: ' . ($s->schedule_3 ?? '-');
        return [
            'title' => 'Vaccination schedule updated',
            'message' => $message,
            'schedule' => [
                'schedule_1' => $s->schedule_1 ?? null,
                'schedule_2' => $s->schedule_2 ?? null,
                'schedule_3' => $s->schedule_3 ?? null,
            ],
            'url' => route('user.vaccinations'),
        ];
    }
}
