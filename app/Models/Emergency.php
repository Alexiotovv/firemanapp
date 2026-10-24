<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'llamante', 'telefono', 'unidades', 'lat', 'lng', 'user_id', 'parte_user_id',
    ];

    protected function casts(): array
    {
        return ['lat' => 'float', 'lng' => 'float'];
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', '!=', 'finalizada');
    }

    public function elements()
    {
        return $this->belongsToMany(AvailabilityElement::class, 'emergency_element', 'emergency_id', 'element_id')->withTimestamps();
    }

    // Sincroniza los vehículos; solo quedan "en emergencia" mientras la emergencia no esté finalizada.
    public function syncVehicles(array $elementIds, bool $active = true): void
    {
        $changes = $this->elements()->sync($elementIds);
        $this->setElementsEnEmergencia($changes['detached'], false);
        $this->setElementsEnEmergencia($elementIds, $active);
        $this->refreshUnidades();
    }

    public function setVehiclesActive(bool $active): void
    {
        $this->setElementsEnEmergencia($this->elements()->pluck('availability_elements.id')->all(), $active);
    }

    private function setElementsEnEmergencia(array $ids, bool $value): void
    {
        if ($value === false && $ids) {
            $busy = DB::table('emergency_element')
                ->join('emergencies', 'emergencies.id', '=', 'emergency_element.emergency_id')
                ->where('emergencies.estado', '!=', 'finalizada')
                ->where('emergencies.id', '!=', $this->id)
                ->whereIn('emergency_element.element_id', $ids)
                ->pluck('emergency_element.element_id')->all();
            $ids = array_diff($ids, $busy);
        }
        if ($ids) {
            if ($value) {
                AvailabilityElement::with('category')->whereIn('id', $ids)->get()->each(fn ($el) => AvailabilityCheck::firstOrCreate(
                    ['element_id' => $el->id, 'user_id' => $el->category->user_id],
                    ['disponible' => true]
                ));
            }
            AvailabilityCheck::whereIn('element_id', $ids)->update(['en_emergencia' => $value]);
        }
    }

    private function refreshUnidades(): void
    {
        $this->update(['unidades' => $this->elements()->orderBy('nombre')->pluck('nombre')->implode(' ') ?: null]);
    }

    public function parteUser()
    {
        return $this->belongsTo(User::class, 'parte_user_id');
    }

    public function parteIncendio()
    {
        return $this->hasOne(ParteIncendio::class);
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