<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    // 
    use HasFactory;

    protected $primaryKey = 'book_id';

    protected $fillable = [ 
        'code',
        'name',
        'description',
        'quantity',
        'discount',
        'status',
    ];


    // relationship

    public function categories (): BelongsToMany {
        return $this->belongsToMany(Categories::class , 'book_category' , 'book_id' , 'category_id', 'book_id' , 'category_id');
    }

    public function authors (): BelongsToMany {
        return $this->belongsToMany(Author::class ,'book_author' , 'book_id', 'author_id', 'book_id', 'author_id');
    }

    public function imagesBook():HasMany {
        return $this->hasMany(BookImage::class, 'book_id','book_id');
    }

    public function ordersDetail():HasMany {
        return $this->hasMany(OrderDetail::class, 'book_id');
    }    

}
