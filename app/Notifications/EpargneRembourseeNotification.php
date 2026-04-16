<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EpargneRembourseeNotification extends Notification
{
    public $epargne;

    public function __construct($epargne)
    {
        $this->epargne = $epargne;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🎉 Votre épargne est arrivée à échéance')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre épargne dans « {$this->epargne->preservation->name} » est arrivée à échéance.")
            ->line("Vous avez reçu :")
            ->line("- Capital : " . number_format($this->epargne->amount, 0, ',', ' ') . " FCFA")
            ->line("- Revenu : " . number_format($this->epargne->revenu_attendu, 0, ',', ' ') . " FCFA")
            ->line("Merci pour votre confiance 🌱")
            ->salutation('— L’équipe BioEnergy');
    }
}