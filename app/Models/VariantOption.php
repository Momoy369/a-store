<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function values()
    {
        return $this->hasMany(VariantValue::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantValues()
    {
        return $this->hasMany(VariantValue::class);
    }
}
