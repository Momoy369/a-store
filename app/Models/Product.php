<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id'); // Pastikan ada field 'parent_id' di tabel categories
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function discount()
    {
        return $this->hasOne(Discount::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
