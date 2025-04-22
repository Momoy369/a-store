<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Order;

class NewOrderNotification extends Notification
{

    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Pesanan baru masuk',
            'body' => 'Pesanan #' . $this->order->id . ' dari ' . $this->order->name,
            'url' => route('admin.orders.show', $this->order->id),
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Pesanan Baru "' . $this->order->name . '" dari pelanggan.')
            ->action('Lihat Pesanan', route('admin.orders.show', $this->order->id))
            ->line('Segera lakukan pengiriman.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pesanan Baru Masuk',
            'message' => 'Pesanan #' . $this->order->id . ' telah dibuat.',
            'url' => route('admin.orders.show', $this->order->id),
        ];
    }
}
