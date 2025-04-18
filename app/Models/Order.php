<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'shipping_address',
        'total_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    const STATUSES = ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'];

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'shipped' => 'info',
            'completed' => 'success',
            'canceled' => 'danger',
            default => 'secondary',
        };
    }

    public function updateStatus(Request $request, Order $order)
    {
        $order->status = $request->input('status');
        $order->save();

        // Jika status menjadi "paid", kurangi stok
        if ($order->status === 'paid') {
            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    $variant = Variant::find($item->variant_id);
                    if ($variant && $variant->stock >= $item->quantity) {
                        $variant->stock -= $item->quantity;
                        $variant->save();
                    }
                } else {
                    $product = Product::find($item->product_id);
                    if ($product && $product->stock >= $item->quantity) {
                        $product->stock -= $item->quantity;
                        $product->save();
                    }
                }
            }
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

}
