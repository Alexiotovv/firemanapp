<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'codigo',
        'grados',
        'fecha_asenso',
        'fecha_graduacion',
        'curso_basicos',
        'curso_tecnicos',
        'curso_liderazgo',
        'telefono',
        'ubo',
        'correo_personal',
        'ultimo_cargo',
        'tipo_sangre',
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
            'fecha_asenso' => 'date',
            'fecha_graduacion' => 'date',
        ];
    }

    // Método para verificar si es administrador
    public function isAdmin()
    {
        return $this->is_admin === true;
    }
}