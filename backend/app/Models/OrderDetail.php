<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_detail_id';

    // Because table name ≠ default plural convention
    protected $table = 'order_detail';

    protected $fillable = [
        'order_id',
        'book_id',
        'book_name',
        'book_code',
        'quantity',
        'original_price',
        'discount_percentage',
        'discount_amount',
        'unit_price',
        'total_amount',
    ];

    protected $casts = [
        'quantity'            => 'integer',
        'original_price'      => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount'     => 'decimal:2',
        'unit_price'          => 'decimal:2',
        'total_amount'        => 'decimal:2',
    ];


    // ─── Modern / clean relationships ───────────────────────────────────

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
        // No third argument → Laravel uses Order::$primaryKey = 'order_id'
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
        // No third argument → Laravel uses Book::$primaryKey = 'book_id'
    }
    // ─── Useful accessors (optional but very practical for online bookstore) ──

    public function getSubtotalAttribute(): float
    {
        return round($this->quantity * $this->unit_price, 2);
    }

    public function getSavingsAttribute(): float
    {
        return round($this->discount_amount, 2);
    }

    public function hasDiscount(): bool
    {
        return $this->discount_amount > 0 || $this->discount_percentage > 0;
    }

    /**
     * Nice display string for discount (used in receipts / order summary)
     */
    public function getDiscountLabelAttribute(): string
    {
        if ($this->discount_percentage > 0) {
            return number_format($this->discount_percentage, 1) . '% off';
        }

        if ($this->discount_amount > 0) {
            return '$' . number_format($this->discount_amount, 2) . ' off';
        }

        return '—';
    }
}
