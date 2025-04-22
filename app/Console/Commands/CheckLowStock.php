<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:check-low-stock';
    protected $signature = 'check:low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek produk dengan stok rendah dan kirimkan notifikasi.';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lowStockProducts = Product::where('stock', '<', 5)->get();

        foreach ($lowStockProducts as $product) {
            Notification::route('mail', 'ronijagat@gmail.com')
                ->notify(new LowStockNotification($product));
        }

        $this->info('Notifikasi stok rendah telah dikirim.');
    }
}
