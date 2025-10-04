<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'subtotal'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    // Relationships
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Methods
    public function getTotalPriceAttribute()
    {
        return ($this->unit_price * $this->quantity) - $this->discount;
    }

    public function getDiscountPercentageAttribute()
    {
        $originalTotal = $this->unit_price * $this->quantity;
        return $originalTotal > 0 ? ($this->discount / $originalTotal) * 100 : 0;
    }
}