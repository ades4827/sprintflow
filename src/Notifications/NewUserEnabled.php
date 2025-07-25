<?php

namespace Ades4827\Sprintflow\Notifications;

use Ades4827\Sprintflow\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserEnabled extends Notification
{
    use Queueable;

    public User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
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
        return (new MailMessage)
            ->subject(config('app.name').' - Nuovo account')
            ->line('Benvenuto '.$this->user->name_formatted.',')
            ->line('ti è stato creato un nuovo account.')
            ->line('Puoi accedere cliccando sul seguente link.')
            ->action('Accedi', route('login'))
            ->line('ATTENZIONE: Se non ti è stata comunicata la password utilizza il tasto Password dimenticata?');
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
