<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class Author extends Model
{
    //
    use HasFactory;

    protected $primaryKey = 'author_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'phone',
        'email',
        'dob',
        'age',
        'code',
        'status',
    ];

    // make full name

    public function first_name () {
        return Attribute::make(
            set: function ($value) {
                $this->attributes['first_name'] = $value;
                $this->attributes['full_name'] = trim($value.' '.($this->attributes['last_name'] ?? ''));
                return $value;
            }
        );
    }

    public function last_name () {
        return Attribute::make(
            set: function ($value) {
                $this->attributes['lasst_name'] = $value;
                $this->attributes['full_name'] = trim(($this->attributes['last_name'] ?? '').' '.$value);
                return $value;
            }
        );
    }

    public function full_name () {
        return Attribute::make(
            get: fn () => $this->full_name
        );
    }

    // make age

    public function dob() : Attribute {
        return Attribute::make(
            set: function ($value) {
                $this->attributes['age'] = Carbon::parse($value)->age;
                return $value;
            }
        );
    }


    public function age() : Attribute {
        return Attribute::make(
            get: fn () => $this->dob?->age
        );
    }    


    // relationship

    public function books ():BelongsToMany {
        return $this->belongsToMany(Book::class , 'book_author' , 'book_id', 'author_id', 'book_id', 'author_id');
    }
    




    protected $casts = [
        'dob' => 'date',
        'age' => 'integer',
    ];
}
