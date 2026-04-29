<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    use HasFactory;

    protected $primaryKey = 'receipt_id';

    protected $fillable = [
        'payment_id',
        'receipt_number',
        'receipt_date',
        'pdf_file_path',
        'status',
    ];

    protected $casts = [
        'receipt_date'   => 'datetime',
        'status'         => 'string',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────────────────

    /**
     * The payment this receipt was generated for
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
        // Assumes Payment model has protected $primaryKey = 'payment_id';
    }

    /**
     * Convenience: get the related order through payment
     */
    public function order()
    {
        return $this->payment->order();
        // or more explicit:
        // return $this->hasOneThrough(Order::class, Payment::class, 'payment_id', 'order_id', 'payment_id', 'order_id');
    }

    // ─── Helpers & Accessors ────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isVoid(): bool
    {
        return $this->status === 'void';
    }

    /**
     * Get full URL to the PDF receipt (for download/email)
     */
    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf_file_path) {
            return null;
        }

        // Adjust storage path according to your config
        // Example: if stored in public disk
        return asset('storage/' . $this->pdf_file_path);

        // Or if using private storage + signed URL:
        // return Storage::disk('s3')->temporaryUrl($this->pdf_file_path, now()->addMinutes(15));
    }

    /**
     * Generate a nice display number with prefix (common in Cambodia)
     * Example: REC-2025-0000123
     */
    public function getFormattedNumberAttribute(): string
    {
        return 'REC-' . $this->receipt_date->format('Y') . '-' . str_pad($this->receipt_id, 7, '0', STR_PAD_LEFT);
    }

    /**
     * Short status label (Khmer + English)
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'សកម្ម / Active',
            'void'   => 'បោះបង់ / Void',
            default  => $this->status,
        };
    }
}