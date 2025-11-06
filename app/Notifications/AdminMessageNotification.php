<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Message;

class AdminMessageNotification extends Notification
{
    use Queueable;

    protected $messageModel;

    public function __construct(Message $message)
    {
        $this->messageModel = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->messageModel->subject ?: 'New message from admin';

        $url = route('user.messages.show', $this->messageModel->id);
        if ($notifiable && property_exists($notifiable, 'role') && $notifiable->role === 'health_staff') {
            $url = route('health_staff.messages.show', $this->messageModel->id);
        }

        return (new MailMessage)
            ->subject($subject)
            ->line($this->messageModel->body)
            ->action('View message', url($url))
            ->line('You can also view messages in the app.');
    }

    public function toArray($notifiable)
    {
        $url = route('user.messages.show', $this->messageModel->id);
        if ($notifiable && property_exists($notifiable, 'role') && $notifiable->role === 'health_staff') {
            $url = route('health_staff.messages.show', $this->messageModel->id);
        }

        return [
            'message_id' => $this->messageModel->id,
            'title' => $this->messageModel->subject ?: 'New message from admin',
            'message' => $this->messageModel->body,
            'sender_id' => $this->messageModel->sender_id,
            'sender_name' => $this->messageModel->sender ? $this->messageModel->sender->name : null,
            'url' => $url,
        ];
    }
}
