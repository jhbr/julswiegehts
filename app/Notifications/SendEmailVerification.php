<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmailVerification extends Notification implements ShouldQueue
{

    use Queueable;

    /**
     * The email verification token.
     *
     * @var string
     */
    public $token;

    /**
     * The datetime, where the verification was requested
     *
     * @var string
     */
    public $email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bestätigung Ihrer E-Mail Adresse')
            ->greeting('Willkommen!')
            ->line('Sie bekommen diese E-Mail, da Sie sich auf unserem Portal registriert haben.')
            ->line('Mit dem folgenden Button bestätigen Sie ihre Email-Adresse.')
            ->action('Email bestätigen', url(config('app.url').route('email.verify', ['token' => $this->token, 'email' => $this->email], false)))
            ->line('Wenn Sie sich nicht auf unserem Portal angemeldet haben, so müssen Sie nichts weiter tun.')
            ->salutation('Mit freundlichen Grüßen');
    }

}
