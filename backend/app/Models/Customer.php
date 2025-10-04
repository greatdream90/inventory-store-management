<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'customer_type',
        'credit_limit',
        'current_debt',
        'loyalty_points',
        'birthdate',
        'is_active'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_debt' => 'decimal:2',
        'loyalty_points' => 'integer',
        'birthdate' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVip($query)
    {
        return $query->where('customer_type', 'vip');
    }

    // Methods
    public function getTotalSpentAttribute()
    {
        return $this->sales()->where('status', 'completed')->sum('total');
    }

    public function getAvailableCreditAttribute()
    {
        return $this->credit_limit - $this->current_debt;
    }

    public function canPurchaseOnCredit($amount)
    {
        return $this->available_credit >= $amount;
    }
}