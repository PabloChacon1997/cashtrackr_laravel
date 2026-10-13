<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $token
    )
    {
        //
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
        $url = url("/auth/reset-password/{$this->token}?email={$notifiable->email}");
        return (new MailMessage)
            ->subject('Reestable tu contraseña')
            ->greeting('¡Hola!')
            ->line('Recibimos una solicitud para reestablecer tu constraseña')
            ->action('Reestalecer Contraseña', $url)
            ->line('Si no solicitaste este servicio, puedes ignorar este mensaje')
            ->salutation('Saludos, CashTrackr');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
