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

    public function users()
    {
        return $this->belongsToMany(User::class, 'availability_category_user', 'category_id', 'user_id')->withTimestamps();
    }

    public function elements()
    {
        return $this->hasMany(AvailabilityElement::class, 'category_id');
    }
}