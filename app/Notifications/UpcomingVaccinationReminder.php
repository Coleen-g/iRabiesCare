<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UpcomingVaccinationReminder extends Notification
{
    use Queueable;

    protected $scheduleDate;
    protected $slotLabel;

    public function __construct($scheduleDate, $slotLabel = null)
    {
        $this->scheduleDate = $scheduleDate;
        $this->slotLabel = $slotLabel;
    }

    public function via($notifiable)
    {
        return $notifiable->email ? ['mail', 'database'] : ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Upcoming Rabies Vaccination Reminder')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('This is a reminder that you have an upcoming rabies vaccination on ' . $this->scheduleDate . '.')
                    ->line('Please make sure to attend the clinic or contact your health staff if you need to reschedule.')
                    ->line('Thank you for keeping your vaccination schedule up to date.');
    }

    public function toArray($notifiable)
    {
        $slotLabel = $this->humanSlotLabel($this->slotLabel);
        return [
            'title' => 'Upcoming vaccination',
            'message' => ($slotLabel ? $slotLabel . ' - ' : '') . 'Vaccination scheduled on ' . $this->scheduleDate,
            'schedule_date' => $this->scheduleDate,
            'slot' => $this->slotLabel,
            'slot_label' => $slotLabel,
            'url' => route('user.vaccinations'),
        ];
    }

    protected function humanSlotLabel($slot)
    {
        return match($slot) {
            'schedule_1' => 'First schedule',
            'schedule_2' => 'Second schedule',
            'schedule_3' => 'Third schedule',
            default => null,
        };
    }
}
