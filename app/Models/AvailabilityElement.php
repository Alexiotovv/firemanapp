<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailabilityElement extends Model
{
    protected $fillable = ['category_id', 'nombre'];

    public function category()
    {
        return $this->belongsTo(AvailabilityCategory::class, 'category_id');
    }

    public function checks()
    {
        return $this->hasMany(AvailabilityCheck::class, 'element_id');
    }
}