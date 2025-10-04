<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'category',
        'user_id',
        'data',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'data' => 'json',
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public static function createLowStockNotification($product)
    {
        return static::create([
            'title' => 'สินค้าในสต๊อกน้อย',
            'message' => "สินค้า {$product->name} เหลือในสต๊อกเพียง {$product->quantity} {$product->unit}",
            'type' => 'warning',
            'category' => 'low_stock',
            'user_id' => null, // แจ้งเตือนทั่วไป
            'data' => [
                'product_id' => $product->id,
                'current_quantity' => $product->quantity,
                'min_quantity' => $product->min_quantity
            ]
        ]);
    }

    public static function createSaleNotification($sale, $userId = null)
    {
        return static::create([
            'title' => 'มีการขายใหม่',
            'message' => "การขาย #{$sale->sale_number} มูลค่า {$sale->total} บาท",
            'type' => 'success',
            'category' => 'sales',
            'user_id' => $userId,
            'data' => [
                'sale_id' => $sale->id,
                'sale_number' => $sale->sale_number,
                'total' => $sale->total
            ]
        ]);
    }
}