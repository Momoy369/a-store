<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
