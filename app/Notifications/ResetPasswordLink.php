<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordLink extends Notification
{
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = "https://groundpass.be/reset-password?token={$this->token}&email={$this->email}";

        return (new MailMessage)
            ->subject('Reset je wachtwoord')
            ->line('Je hebt deze e-mail ontvangen omdat je een wachtwoordreset hebt aangevraagd.')
            ->action('Reset wachtwoord', $url)
            ->line('Als je dit niet was, hoef je niets te doen.');
    }
}
