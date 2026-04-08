<?php
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NouvelleEpargneNotification extends Notification
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
            ->subject('✅ Épargne enregistrée')
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre épargne dans « {$this->epargne->preservation->name} » a été enregistrée avec succès.")
            ->line("Montant investi : " . number_format($this->epargne->amount, 0, ',', ' ') . " FCFA")
            ->line("Revenu attendu : " . number_format($this->epargne->revenu_attendu, 0, ',', ' ') . " FCFA")
            ->line("Échéance : " . \Carbon\Carbon::parse($this->epargne->end_date)->format('d/m/Y'))
            ->salutation('— L’équipe BioEnergy');
    }
}