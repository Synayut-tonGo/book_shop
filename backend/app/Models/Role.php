<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    //

    use HasFactory;

    protected $primaryKey = 'role_id';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'created_by',
    ];

    // relationship

    public function users():BelongsToMany {
        return $this->belongsToMany(User::class , 'user_role' , 'user_id' , 'role_id' , 'user_id' , 'role_id')->withPivot('assigned_by')->withTimestamps();
    }
    public function permissions(): BelongsToMany
    {
            return $this->belongsToMany(
                Permission::class,
                'role_permission',
                'role_id',
                'permission_id',
                'role_id',
                'permission_id'
            )->withPivot('assigned_by')->withTimestamps();
    }
    public function creators():BelongsTo {
        return $this->belongsTo(User::class, 'created_by' , 'user_id');
    }
}
