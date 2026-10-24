<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailabilityCategory extends Model
{
    protected $fillable = ['nombre', 'icono', 'activo'];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function elements()
    {
        return $this->hasMany(AvailabilityElement::class, 'category_id');
    }
}