<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BonusParrainageNotification extends Notification
{
    public $montant;
    public $filleul;

    public function __construct($montant, $filleul)
    {
        $this->montant = $montant;
        $this->filleul = $filleul;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🎁 Bonus de parrainage reçu')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Vous avez reçu un bonus de parrainage grâce à l’activité de votre filleul : {$this->filleul}")
            ->line("Montant reçu : " . number_format($this->montant, 0, ',', ' ') . " FCFA")
            ->line("Continuez à inviter pour maximiser vos gains 🚀")
            ->salutation('— L’équipe BioEnergy');
    }
}