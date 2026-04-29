<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    //
    use HasFactory;

    protected $primaryKey = 'permission_id';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'created_by',
    ];

    // relationship

    public function roles():BelongsToMany {
            return $this->belongsToMany(Role::class, 'role_permission', 'role_id' , 'permission_id','role_id' , 'permission_id')->withPivot('assigned_by')->withTimestamps();
    }

    public function creator ():BelongsTo {
            return $this->belongsTo(User::class, 'created_by' , 'user_id');
    }   

}
