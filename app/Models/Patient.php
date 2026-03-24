<?php

namespace App\Models;

use App\Models\User;
use App\Models\RendezVous;

class Patient extends User
{
    protected static function booted()
    {
        static::addGlobalScope('patient', function ($builder) {
            $builder->where('role', 'PATIENT');
        });
    }
    //relations
    public function RendezVous()
    {
        return $this->hasMany(RendezVous::class , 'patient_id');
    }
}
