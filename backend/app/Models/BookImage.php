<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookImage extends Model
{
    //

    use HasFactory;

    protected $primaryKey = 'book_image_id';

    protected $fillable = [
        'book_id',
        'image',
        'type',  
        'upload_date',
    ];

    // relationship
    public function books() : BelongsTo {
        return $this->belongsTo(Book::class , 'book_id', 'book_id');
    }

    
    protected $casts = [
        'upload_date' => 'dateTime'
    ];


    public function scopeCover ($query) {
        return $query->where('type' , 'cover');
    }
    public function scopeProfile ($query) {
        return $query->where('type' , 'profile');
    }
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

}
