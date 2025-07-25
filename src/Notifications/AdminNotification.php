<?php

namespace Ades4827\Sprintflow\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    protected array $ccUsers;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $notification_body, string $type = 'error', array $ccUsers = [])
    {
        $this->notification_body = $notification_body;
        $this->type = $type;
        $this->ccUsers = $ccUsers;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if ($this->type === 'error') {
            $mailMessage = (new MailMessage)
                ->subject(config('app.name').' - Notifica errore')
                ->line('Si è verificato un errore su: '.config('app.name'))
                ->line('Dettagli errore: '.$this->notification_body)
                ->greeting(config('app.name'));
        } else {
            $mailMessage = (new MailMessage)
                ->subject(config('app.name').' - Notifica')
                ->line('Nuova notifica per: '.config('app.name'))
                ->line($this->notification_body)
                ->greeting(config('app.name'));
        }

        if (!empty($this->ccUsers)) {
            $mailMessage->cc($this->ccUsers);
        }

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
