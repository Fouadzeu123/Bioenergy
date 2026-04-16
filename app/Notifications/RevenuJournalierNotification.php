<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RevenuJournalierNotification extends Notification
{
    public $montant;

    public function __construct($montant)
    {
        $this->montant = $montant;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('💰 Revenu journalier crédité')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre revenu journalier a été crédité avec succès.")
            ->line("Montant reçu : " . number_format($this->montant, 0, ',', ' ') . " FCFA")
            ->line("Merci pour votre engagement envers BioEnergy 🌱")
            ->salutation('— L’équipe BioEnergy');
    }
}