<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Addresses extends Model
{
    use HasFactory;

    protected $primaryKey = 'address_id';

    protected $fillable = [
        'user_id',
        'address_name',     // e.g. "Home", "Office", "សាខា 1", "ផ្ទះម្តាយ"
        'description',      // full address text: street, village, commune, district...
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

// ─── Relationships ──────────────────────────────────────────────

    /**
     * The user who owns this address
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
        // assumes User model has protected $primaryKey = 'user_id';
    }

    /**
     * All orders that use this address as delivery address
     * (useful for showing order history per address)
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'address_id');
        // assumes Order model has protected $primaryKey = 'order_id';
    }


    // helper 

    public function isActive():bool {
        return $this->status == 'active';
    }

    // format loacation 

    public function getLabelAttribute():string {
        $parts = [
            $this->address_name,
            //Str is a class for access limit
            //limit (value, length , ...)
            Str::limit($this->description,40,'...')
        ];
        // implode do is merge array like ex: (Home(address_name) - street 2004.... (description))
        return implode('-',array_filter($parts));
    }


    // this function prevent description have many line
    public function getShortDescription():string {
                //convert decription into array
        $line = explode('\n',trim($this->description));
        $short= implode(' ,', array_slice($line ,0,3));
        return Str::limit($short, 60, '...');
    }

}
