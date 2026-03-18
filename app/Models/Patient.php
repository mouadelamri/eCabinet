<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    //methods
    public function register()
    {

    }
    public function requestAppointment()
    {

    }
    public function viewMyProgress()
    {

    }
}
