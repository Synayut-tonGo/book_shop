<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categories extends Model
{
    //

    use HasFactory;

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    // relationship

    public function books (): BelongsToMany {
        return $this->belongsToMany(Book::class , 'book_category' , 'book_id' , 'category_id', 'book_id' , 'category_id');
    }
    
}
