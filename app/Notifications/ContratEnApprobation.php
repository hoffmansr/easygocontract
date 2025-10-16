<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Contractant;

class ContratEnApprobation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $contrat, $notifyContractants;
    public function __construct($contrat, $notifyContractants = false)
{
    $this->contrat = $contrat;
    $this->notifyContractants = $notifyContractants;
}

public function via($notifiable)
{
    $channels = ['database'];
    if ($notifiable instanceof Contractant && $this->notifyContractants) {
        $channels[] = 'mail';
    } elseif (!($notifiable instanceof Contractant)) {
        $channels[] = 'mail';
    }
    return $channels;
}


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                   ->subject('Nouveau contrat en approbation')   // Sujet de l’email
                    ->greeting('Bonjour ' . $notifiable->name . ',') // Salutation personnalisée
                    ->line('Un contrat a été envoyé en approbation et nécessite votre intervention.') // Texte du mail
                    ->action('Voir le contrat', url('/contrats')) // Bouton "Voir le contrat" qui redirige vers la liste
                    ->line('Merci d’utiliser notre application de gestion des contrats.'); // Texte de fin
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'titre' => $this->contrat->titre ?? 'Contrat',
            'contrat_id' => $this->contrat->id ?? null,
            'societe_id' => $this->contrat->societe_id,
            'message' => "Le contrat {$this->contrat->titre} est en attente d’approbation."
            //
        ];
    }
}
