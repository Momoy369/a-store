<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    use HasFactory;

    protected $fillable = ['variant_option_id', 'value'];

    public function option()
    {
        return $this->belongsTo(VariantOption::class, 'variant_option_id');
    }

    public function variantOption()
    {
        return $this->belongsTo(VariantOption::class);
    }
}