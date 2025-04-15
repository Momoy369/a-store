<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'value',
        'stock',
        'price',
        'discount_type',
        'discount_value',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFinalPriceAttribute()
    {
        $basePrice = $this->price ?? $this->product->price;

        if ($this->discount_type && $this->discount_value) {
            if ($this->discount_type === 'percent') {
                return $basePrice - ($basePrice * $this->discount_value / 100);
            } elseif ($this->discount_type === 'fixed') {
                return $basePrice - $this->discount_value;
            }
        }

        return $basePrice;
    }

    public function getDiscountAmountAttribute()
    {
        if ($this->discount_type == 'percent') {
            return $this->price * ($this->discount_percentage / 100);
        } elseif ($this->discount_type == 'fixed') {
            return $this->discount_value;
        }
        return 0;
    }
}
