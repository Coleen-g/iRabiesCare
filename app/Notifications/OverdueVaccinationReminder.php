<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OverdueVaccinationReminder extends Notification
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
                    ->subject('Overdue Rabies Vaccination Reminder')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Our records show that a scheduled rabies vaccination on ' . $this->scheduleDate . ' was not recorded.')
                    ->line('Please contact your health staff or visit the clinic as soon as possible to complete the dose.')
                    ->line('If you already had the dose, please inform the clinic so we can update our records.');
    }

    public function toArray($notifiable)
    {
        $slotLabel = $this->humanSlotLabel($this->slotLabel);
        return [
            'title' => 'Overdue vaccination',
            'message' => ($slotLabel ? $slotLabel . ' - ' : '') . 'Vaccination was scheduled on ' . $this->scheduleDate . ' but not recorded.',
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
