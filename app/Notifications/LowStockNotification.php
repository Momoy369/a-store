<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ProductVariantCombination;

class LowStockNotification extends Notification
{
    use Queueable;

    public $combination;

    /**
     * Create a new notification instance.
     */
    public function __construct(ProductVariantCombination $combination)
    {
        $this->combination = $combination;
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
        $variantDetails = $this->getVariantDetails();

        return [
            'title' => 'Stok Produk Rendah',
            'body' => 'Stok produk "' . $this->combination->product->name . '" (' . $variantDetails . ') hanya tersisa ' . $this->combination->stock . ' unit.',
            'url' => route('admin.products.show', $this->combination->product->id),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $variantDetails = $this->getVariantDetails();

        return (new MailMessage)
            ->line('Stok produk "' . $this->combination->product->name . '" (' . $variantDetails . ') hampir habis.')
            ->action('Lihat Produk', route('admin.products.show', $this->combination->product->id))
            ->line('Segera lakukan restock.');
    }

    public function toArray(object $notifiable): array
    {
        $variantDetails = $this->getVariantDetails();

        return [
            'title' => 'Stok Produk Rendah',
            'message' => 'Stok produk "' . $this->combination->product->name . '" (' . $variantDetails . ') hanya tersisa ' . $this->combination->stock . ' unit.',
            'url' => route('admin.products.show', $this->combination->product->id),
        ];
    }

    private function getVariantDetails(): string
    {
        return $this->combination->variantValues->map(function ($variantValue) {
            return $variantValue->variantOption->name . ': ' . $variantValue->value;
        })->join(', ');
    }

}
