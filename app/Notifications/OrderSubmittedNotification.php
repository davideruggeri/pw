<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderSubmittedNotification extends Notification
{
    use Queueable;

    public $ordine;

    /**
     * Create a new notification instance.
     */
    public function __construct($ordine)
    {
        $this->ordine = $ordine;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nuovo Ordine Ricevuto #' . $this->ordine->IDOrdineVendita)
            ->greeting('Ciao ' . $notifiable->name . ',')
            ->line('È stato appena confermato un nuovo ordine dal cliente ' . ($this->ordine->cliente->Nome ?? 'Sconosciuto') . '.')
            ->line('Totale ordine: €' . number_format($this->ordine->totale_ordine, 2))
            ->action('Gestisci Ordine', route('orders.show', $this->ordine->IDOrdineVendita))
            ->line('Accedi al gestionale per approvare o gestire la richiesta.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ordine_id' => $this->ordine->IDOrdineVendita,
            'messaggio' => 'Nuovo ordine #' . $this->ordine->IDOrdineVendita . ' da confermare',
            'totale' => $this->ordine->totale_ordine,
        ];
    }
}
