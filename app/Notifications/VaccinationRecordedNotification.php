<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VaccinationRecordedNotification extends Notification
{
    use Queueable;

    protected $vaccination;

    public function __construct($vaccination)
    {
        $this->vaccination = $vaccination;
    }

    public function via($notifiable)
    {
        return $notifiable->email ? ['mail', 'database'] : ['database'];
    }

    public function toMail($notifiable)
    {
        $v = $this->vaccination;
        return (new MailMessage)
                    ->subject('Vaccination Recorded')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('A vaccination dose has been recorded for you on ' . ($v->date_given ? $v->date_given : 'N/A') . '.')
                    ->line('Vaccine: ' . ($v->vaccine ?? '-'))
                    ->line('Dose: ' . ($v->dose ?? '-'))
                    ->line('Administered by: ' . ($v->administered_by ?? '-'))
                    ->line('If this is incorrect, please contact your health staff.');
    }

    public function toArray($notifiable)
    {
        $v = $this->vaccination;
        return [
            'title' => 'Vaccination recorded',
            'message' => 'A vaccination was recorded on ' . ($v->date_given ?? 'N/A'),
            'vaccination_id' => $v->id ?? null,
            'url' => route('user.vaccinations'),
        ];
    }
}
