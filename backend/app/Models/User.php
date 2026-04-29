<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    protected $primaryKey = 'user_id';


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'phone',
        'email',
        'password',
        'dob',
        'gender',
        'age',
        'image',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dob'=>'date',
            'age'=>'integer',
            'password' => 'hashed',
        ];
    }

    // make full name

    public function firstName (): Attribute {

        return Attribute::make(
            set: function ($value) {
                $this->attributes['first_name'] = $value;
                $this->attributes['full_name'] = trim($value. ' ' . ($this->last_name ?? ''));
                return $value;
            }
        );

    }

    public function lastName (): Attribute {

        return Attribute::make(
            set: function ($value) {
                $this->attributes['last_name'] = $value;
                $this->attributes['full_name'] = trim(($this->first_name ?? ''). ' ' . $value);
                return $value;
            }
        );
    }
    protected static function booted()
    {
        static::creating(function (User $user) {
            if ($user->first_name || $user->last_name) {
                $user->full_name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            }
            // Optional: clean up extra spaces, handle only one name, etc.
            $user->full_name = preg_replace('/\s+/', ' ', $user->full_name ?? '');
        });

        // Optional: also on updating if names can change later
        static::updating(function (User $user) {
            if ($user->isDirty(['first_name', 'last_name'])) {
                $user->full_name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                $user->full_name = preg_replace('/\s+/', ' ', $user->full_name);
            }
        });
        static::creating(function (User $user) {
            if ($user->dob) {
                $user->age = Carbon::parse($user->dob)->age;
            }
            // Optional: clean up full_name just in case
            if ($user->first_name || $user->last_name) {
                $user->full_name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            }
        });

        // Optional — if users can change their birthday later
        static::updating(function (User $user) {
            if ($user->isDirty('dob')) {
                $user->age = $user->dob ? Carbon::parse($user->dob)->age : null;
            }
        });
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

    public function roles (): BelongsToMany {
        return $this->belongsToMany(Role::class, 'user_role' , 'user_id', 'role_id','user_id', 'role_id')->withPivot('assigned_by')->withTimestamps();
    }

    public function createdRoles(): HasMany {
        return $this->hasMany(Role::class , 'created_by' , 'user_id');
    }
    public function orders(): HasMany {
        return $this->hasMany(Order::class , 'user_id' , 'user_id');
    }
    public function carts(): HasMany {
        return $this->hasMany(Cart::class , 'user_id' , 'user_id');
    }
    public function CreatedPermissions(): HasMany {
        return $this->hasMany(Permission::class , 'created_by' , 'user_id');
    }

    public function assignmentsMade():BelongsToMany {
            return $this->belongsToMany(
                User::class,        // Related model: the user who received the role
                'user_role',        // Pivot table
                'assigned_by',      // Foreign key on pivot for THIS user (the assigner)
                'user_id'           // Foreign key on pivot for RELATED user (the receiver)
            )
            ->withPivot('role_id', 'assigned_by') // Include extra pivot columns
            ->withTimestamps();
    }

    public function rolesAssigned(): BelongsToMany { 
        return $this->belongsToMany( Role::class, 'user_role', 'assigned_by', 'role_id') ->withPivot('user_id', 'assigned_by') ->withTimestamps(); }


    public function permissionsAssigned(): BelongsToMany
    {
    return $this->belongsToMany(
        Permission::class,      // Related model
        'role_permission',      // Pivot table
        'assigned_by',          // FK in pivot for this user
        'permission_id'         // FK in pivot pointing to Permission
    )
    ->withPivot('role_id', 'assigned_by')
    ->withTimestamps();
    }


    // helper

    public function hasRole (string $roleSlug){
        return $this->roles()->where('slug' , $roleSlug)->exists();
    }

    public function hasAnyRole (string $roleSlug){
        return $this->roles()->whereIn('slug' , $roleSlug)->exists();
    }

    public function hasPermission(string $permissionSlug){
        return $this->roles()->whereHas('permissions', fn($query) => $query->where('slug', $permissionSlug))->exists();
    }

    public function getAllpermissions(){
        return $this->roles()->with('permissions')->get()->pluck('permerssions.*.slug')->flatten()->unique()->values()->toArray();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'remember_token',
    ];
}
