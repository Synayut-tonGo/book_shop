<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ImportBook extends Model
{
    //
    use HasFactory;
    protected $primaryKey = 'import_book_id';
    

    protected $fillable = [
        'book_id',
        'publisher_id',
        'stock',
        'spoiled',
        'usable',
        'image',
    ];

    protected $casts = [
        'stocked_at' => 'dateTime'
    ];

    // make auto get stock 
    protected static function booted()
    {
        static::creating( function(ImportBook $import) {
            $import->stocked_at = Carbon::now();
        });
    }
    protected function stockedAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn (Carbon $value) => $value->format('d/m/Y H:i')
        );
    }

    // relationship

    public function books():BelongsTo {

        return $this->belongsTo(Book::class , 'book_id');

    }

    public function publishers():BelongsTo {

        return $this->belongsTo(Publisher::class , 'publisher_id');

    }

    

}
