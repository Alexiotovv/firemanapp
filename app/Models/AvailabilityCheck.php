<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailabilityCheck extends Model
{
    protected $fillable = ['element_id', 'user_id', 'disponible'];

    protected function casts(): array
    {
        return ['disponible' => 'boolean'];
    }

    public function element()
    {
        return $this->belongsTo(AvailabilityElement::class, 'element_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}