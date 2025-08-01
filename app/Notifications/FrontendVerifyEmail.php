<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FrontendVerifyEmail extends Notification
{
    /**
     * De “platte” token die we via de e-mail-link meesturen.
     */
    protected string $plainToken;

    public function __construct(string $plainToken)
    {
        $this->plainToken = $plainToken;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Base-URL van je frontend, in .env ingesteld als APP_FRONTEND_URL
        $frontendUrl = config('app.frontend_url', 'https://groundpass.be');

        // Bouw de link naar Next.js/email-confirmed met query-string:
        $url = $frontendUrl
            . '/email-confirmed?verify_token=' . $this->plainToken
            . '&email=' . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Bevestig je Groundpass-account')
            ->greeting('Hallo ' . $notifiable->name . ',')
            ->line('Bedankt voor je registratie! Klik op de knop hieronder om je e-mailadres te verifiëren:')
            ->action('Bevestig e-mailadres', $url)
            ->line('Als je deze registratie niet hebt aangevraagd, kun je deze e-mail negeren.');
    }
}
