<?php

namespace App\Models;

use App\Models\User;
use App\Models\RendezVous;

class Doctor extends User
{
    protected static function booted()
    {
        static::addGlobalScope('doctor', function ($builder) {
            $builder->where('role', 'DOCTOR');
        });
    }
    //relations
    public function RendezVous()
    {
        return $this->hasMany(RendezVous::class, 'medecin_id');
    }
    //methods
    public function viewSchedule()
    {
     
    }
    public function viewPatientProgress($patient_id)
    {

    }
}
