<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Profile;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'apellidos',
        'compania',
        'dni',
        'is_admin',
        'email',
        'password',
        // profile fields moved to profiles table
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // Método para verificar si es administrador
    public function isAdmin()
    {
        return $this->is_admin === true;
    }

    public function availabilityCategories()
    {
        return $this->belongsToMany(AvailabilityCategory::class, 'availability_category_user', 'user_id', 'category_id')->withTimestamps();
    }

    // Relación con el perfil
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}