<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publisher extends Model
{
    //

    use HasFactory;

    protected $primaryKey = 'publisher_id';

    protected $fillable = [
        'publisher_name',
        'code',
        'status',
    ];


    // relationship 

    public function importsBook (): HasMany {
        return $this->hasMany(ImportBook::class , 'publisher_id');
    }
}
