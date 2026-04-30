<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'nombres_apellidos',
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
        'dni',
        'ultimo_cargo',
        'tipo_sangre',
    ];

    protected $casts = [
        'fecha_asenso' => 'date',
        'fecha_graduacion' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
