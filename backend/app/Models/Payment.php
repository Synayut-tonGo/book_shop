<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_id',
        'method',
        'amount',
        'paid_datetime',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'paid_datetime'  => 'datetime',
        'status'         => 'string',
        'method'         => 'string',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────────────────

    /**
     * The order this payment belongs to
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
        // Assumes Order model has protected $primaryKey = 'order_id';
    }

    // ─── Helpers & Accessors ────────────────────────────────────────

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function isPending(): bool
    {
        return $this->status === 'unpaid';
    }

    /**
     * Human-readable payment method name (useful for receipts / admin panel)
     */
    public function getMethodLabelAttribute(): string
    {
        return match ($this->method) {
            'cash' => 'សាច់ប្រាក់ (Cash)',
            'qr'   => 'QR Code / Mobile Banking',
            default => ucfirst($this->method),
        };
    }

    /**
     * Status badge text/color helper (for admin dashboard or order view)
     */
    public function getStatusLabelAttribute(): array
    {
        return match ($this->status) {
            'paid'     => ['text' => 'បានទូទាត់',     'class' => 'success'],
            'unpaid'   => ['text' => 'មិនទាន់ទូទាត់',   'class' => 'warning'],
            'refunded' => ['text' => 'បានសងវិញ',     'class' => 'danger'],
            default    => ['text' => $this->status,    'class' => 'secondary'],
        };
    }

    /**
     * Quick check if this payment matches the order total
     * (useful when validating before marking order as completed)
     */
    public function matchesOrderTotal(): bool
    {
        return $this->order && abs($this->amount - $this->order->total_amount) < 0.01;
    }
}