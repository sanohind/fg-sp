<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'name',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Mutator untuk hash password
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Relationship dengan Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Relationship dengan LogStorePull
    public function logStorePulls()
    {
        return $this->hasMany(LogStorePull::class, 'user_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role->role_name === 'Admin' || $this->role->role_name === 'Super Admin';
    }

    public function isSuperAdmin()
    {
        return $this->role->role_name === 'Super Admin';
    }
}
