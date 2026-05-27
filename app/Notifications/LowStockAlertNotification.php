<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockAlertNotification extends Notification
{
    use Queueable;

    public $prodotto;

    /**
     * Create a new notification instance.
     */
    public function __construct($prodotto)
    {
        $this->prodotto = $prodotto;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'prodotto_id' => $this->prodotto->CodiceUnivoco,
            'messaggio' => "Il prodotto {$this->prodotto->NomeProdotto} (#{$this->prodotto->CodiceUnivoco}) è sotto scorta! Giacenza attuale: {$this->prodotto->Giacenza} (Scorta Minima: {$this->prodotto->ScortaMinima}).",
            'giacenza' => $this->prodotto->Giacenza,
            'minima' => $this->prodotto->ScortaMinima,
        ];
    }
}
