<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParteEmergencia extends Model
{
    use HasFactory;

    protected $table = 'parte_emergencias';
    
    protected $fillable = [
        'numero_parte',
        'fecha',
        'hora_salida',
        'hora_ingreso',
        'piloto_maquina',
        'velocimetro_salida',
        'velocimetro_retorno',
        'clasificacion_servicio',
        'tipo_lugar',
        'direccion_incidente',
        'traslado_a',
        'persona_responsable',
        'nombre_afectado',
        'dni_afectado',
        'edad_afectado',
        'sexo_afectado',
        'presion_arterial',
        'pulso',
        'respiracion',
        'temperatura',
        'neurologico_consciente',
        'neurologico_inconsciente',
        'pupilas',
        'spo2',
        'lesiones_observadas',
        'diagnostico_presuntivo',
        'tratamiento_efectuado',
        'material_utilizado',
        'observaciones',
        'unidades_cgbvp',
        'unidades_policiales',
        'concurrentes',
        'no_concurrentes',
        'total_concurrentes',
        'total_no_concurrentes',
        'firma_bombero_mando',
        'firma_bombero_confecciona',
        'user_id',
        'compania_id',
        'completado',
        'aprobado',
    ];

    protected $casts = [
        'clasificacion_servicio' => 'array',
        'fecha' => 'date',
        'completado' => 'boolean',
        'aprobado' => 'boolean',
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la compañía
    public function compania()
    {
        return $this->belongsTo(User::class, 'compania_id');
    }

    // Método para obtener opciones de clasificación
    public static function getClasificaciones()
    {
        return [
            'herido' => 'Herido',
            'accidente_automovilistico' => 'Accidente Automovilístico',
            'traslado_herido' => 'Traslado de herido',
            'traslado_enfermo' => 'Traslado de Enfermo',
            'otros' => 'Otros',
            'incendio_apoyo' => 'Incendio (apoyo)',
            'servicio_cancelado' => 'Servicio cancelado',
            'atentado' => 'Atentado',
            'atropellado' => 'Atropellado',
        ];
    }

    // Accessor para clasificación de servicio
    public function getClasificacionServicioAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    // Mutator para clasificación de servicio
    public function setClasificacionServicioAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['clasificacion_servicio'] = json_encode(array_values($value));
        } else {
            $this->attributes['clasificacion_servicio'] = $value;
        }
    }

    // Método para marcar como completado
    public function marcarCompletado()
    {
        $this->completado = true;
        $this->save();
        return $this;
    }

    // Método para aprobar
    public function aprobar()
    {
        $this->aprobado = true;
        $this->save();
        return $this;
    }
}