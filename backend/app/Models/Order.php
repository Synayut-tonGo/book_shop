<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'address_id',
        'subtotal',
        'total_discount',
        'total_amount',
        'order_date',
        'status',
    ];

    protected $casts = [
        'subtotal'       => 'decimal:2',
        'total_discount' => 'decimal:2',
        'total_amount'   => 'decimal:2',
        'order_date'     => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────────────────

    // The customer who made this order
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // The delivery address (null = pickup at store or walk-in)
    public function address(): BelongsTo
    {
        return $this->belongsTo(Addresses::class, 'address_id', 'address_id');
    }

    // All the books/items inside this order
    public function items(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
}