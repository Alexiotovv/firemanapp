<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParteIncendio extends Model
{
    use HasFactory;

    protected $table = 'parte_incendios';
    
    protected $fillable = [
        'numero_parte',
        'clasificacion_servicio',
        'clasificacion_servicio_texto',
        'fecha',
        'hora_salida',
        'hora_retorno',
        'persona_recepciono_aviso',
        'maquina',
        'mando_maquina',
        'piloto_maquina',
        'direccion_emergencia',
        'causa_emergencia',
        'clase_inmueble',
        'tipo_construccion',
        'vehiculo',
        'modelo_vehiculo',
        'placa_vehiculo',
        'color_vehiculo',
        'marca_vehiculo',
        'danos_vehiculo',
        'personas_lesionadas',
        'trabajo_efectuado',
        'material_utilizado',
        'unidades_apoyo_cgbvp',
        'mando_operaciones_cgbvp',
        'unidades_policiales',
        'personal_asistente',
        'personal_bombero_accidentado',
        'personal_asistente_cuartel',
        'damnificados_incendios',
        'observaciones',
        'firma_bombero_mando',
        'firma_bombero_confecciona',
        'firma_segundo_jefe',
        'user_id',
        'compania_id',
        'completado',
        'aprobado',
    ];

    protected $casts = [
        'personas_lesionadas' => 'array',
        'personal_asistente' => 'array',
        'fecha' => 'date',
        'completado' => 'boolean',
        'aprobado' => 'boolean',
    ];

    // Relación con el usuario que creó el parte
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la compañía
    public function compania()
    {
        return $this->belongsTo(User::class, 'compania_id');
    }

    // Scope para partes del usuario
    public function scopeUserPartes($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope para partes de la compañía
    public function scopeCompaniaPartes($query, $compania)
    {
        return $query->where('compania_id', $compania);
    }

    // Método para obtener la clasificación de servicio como texto
    public function getClasificacionServicioTextoAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $clasificaciones = [
            1 => 'INCENDIOS',
            2 => 'FUGA DE GAS',
            3 => 'EMERGENCIAS MEDICAS',
            4 => 'RESCATES',
            5 => 'DERRAMES',
            6 => 'CORTO CIRCUITO',
            7 => 'SERVICIO ESPECIAL',
            8 => 'ACCIDENTE VEHICULAR',
            9 => 'FALSA ALARMA',
            10 => 'VARIOS',
            11 => 'COMISIONES',
            12 => 'AMAGO DE INCENDIO',
        ];

        return $clasificaciones[$this->clasificacion_servicio] ?? 'DESCONOCIDO';
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

    // En app/Models/ParteIncendio.php, después de los casts

    // Método para obtener personas lesionadas como array
    public function getPersonasLesionadasAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        // Intentar decodificar JSON
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    // Método para obtener personal asistente como array
    public function getPersonalAsistenteAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_array($value)) {
            return $value;
        }
        
        // Intentar decodificar JSON
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    // Método para establecer personas lesionadas
    public function setPersonasLesionadasAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['personas_lesionadas'] = json_encode(array_values($value));
        } else {
            $this->attributes['personas_lesionadas'] = $value;
        }
    }

    // Método para establecer personal asistente
    public function setPersonalAsistenteAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['personal_asistente'] = json_encode(array_values($value));
        } else {
            $this->attributes['personal_asistente'] = $value;
        }
    }
}