<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    public const TIPOS = [
        'incendio_estructural' => ['Incendio estructural', 'bi-fire'],
        'incendio_terreno' => ['Incendio / Terreno baldío', 'bi-fire'],
        'accidente_vehicular' => ['Accidente vehicular', 'bi-car-front-fill'],
        'emergencia_medica' => ['Emergencia médica', 'bi-heart-pulse-fill'],
        'inundacion' => ['Inundación', 'bi-water'],
        'rescate' => ['Rescate', 'bi-life-preserver'],
        'materiales_peligrosos' => ['Materiales peligrosos', 'bi-radioactive'],
        'otro' => ['Otro', 'bi-exclamation-triangle-fill'],
    ];

    public const ESTADOS = [
        'en_evaluacion' => 'En evaluación',
        'despachada' => 'Despachada',
        'en_camino' => 'En camino',
        'en_sitio' => 'En sitio',
        'atendiendo' => 'Atendiendo',
        'controlada' => 'Controlada',
        'finalizada' => 'Finalizada',
    ];

    public const PRIORIDADES = ['alta' => 'Alta', 'media' => 'Media', 'baja' => 'Baja'];

    protected $fillable = [
        'numero', 'tipo', 'prioridad', 'estado', 'direccion', 'referencia', 'descripcion',
        'llamante', 'telefono', 'unidades', 'lat', 'lng', 'user_id',
    ];

    protected function casts(): array
    {
        return ['lat' => 'float', 'lng' => 'float'];
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', '!=', 'finalizada');
    }

    public function getTipoLabelAttribute(): string
    {
        return self::TIPOS[$this->tipo][0] ?? $this->tipo;
    }

    public function getTipoIconoAttribute(): string
    {
        return self::TIPOS[$this->tipo][1] ?? 'bi-exclamation-triangle-fill';
    }

    public function getEstadoLabelAttribute(): string
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }

    public static function nextNumero(): string
    {
        $year = now()->year;
        $n = static::whereYear('created_at', $year)->count() + 1;
        do {
            $numero = sprintf('%d-%05d', $year, $n++);
        } while (static::where('numero', $numero)->exists());

        return $numero;
    }
}