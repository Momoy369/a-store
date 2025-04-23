<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ProductVariantCombination extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'product_id',
        'stock',
        'price',
        'discount_type',
        'discount_value',
    ];

    protected $casts = [
        'variant_value_ids' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFinalPriceAttribute()
    {
        $basePrice = $this->price;

        if ($this->discount_type && $this->discount_value) {
            if ($this->discount_type === 'percent') {
                return $basePrice - ($basePrice * $this->discount_value / 100);
            } elseif ($this->discount_type === 'fixed') {
                return $basePrice - $this->discount_value;
            }
        }

        return $basePrice;
    }

    public function variantValues()
    {
        return $this->belongsToMany(VariantValue::class, 'variant_combination_variant_value', 'variant_combination_id', 'variant_option_id');
    }
}
