<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'unit_cost',
        'reference_type',
        'reference_id',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
        'unit_cost' => 'decimal:2'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeIncoming($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeOutgoing($query)
    {
        return $query->where('type', 'out');
    }

    public function scopeAdjustments($query)
    {
        return $query->where('type', 'adjustment');
    }

    // Methods
    public static function createTransaction($productId, $userId, $type, $quantity, $data = [])
    {
        $product = Product::find($productId);
        if (!$product) {
            return false;
        }

        $quantityBefore = $product->quantity;
        
        // คำนวณ quantity หลังจากการเปลี่ยนแปลง
        $quantityAfter = $quantityBefore;
        if ($type === 'in') {
            $quantityAfter += abs($quantity);
        } elseif ($type === 'out') {
            $quantityAfter -= abs($quantity);
        } elseif ($type === 'adjustment') {
            $quantityAfter = $quantity; // ปรับเป็นจำนวนที่กำหนด
        }

        // อัพเดทจำนวนสินค้าในฐานข้อมูล
        $product->update(['quantity' => $quantityAfter]);

        // สร้าง transaction record
        return static::create([
            'product_id' => $productId,
            'user_id' => $userId,
            'type' => $type,
            'quantity' => $type === 'adjustment' ? ($quantityAfter - $quantityBefore) : 
                         ($type === 'out' ? -abs($quantity) : abs($quantity)),
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'unit_cost' => $data['unit_cost'] ?? null,
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}