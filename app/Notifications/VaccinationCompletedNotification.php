<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VaccinationCompletedNotification extends Notification
{
    use Queueable;

    protected $completedOn;

    public function __construct($completedOn = null)
    {
        $this->completedOn = $completedOn;
    }

    public function via($notifiable)
    {
        return $notifiable->email ? ['mail', 'database'] : ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Vaccination Course Completed')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Congratulations — our records show that your rabies vaccination course is complete' . ($this->completedOn ? ' on ' . $this->completedOn : '') . '.')
                    ->line('No further routine rabies doses are required at this time. If you have concerns, contact your health staff.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Vaccination course completed',
            'message' => 'Your rabies vaccination course is marked completed.',
            'completed_on' => $this->completedOn,
        ];
    }
}
