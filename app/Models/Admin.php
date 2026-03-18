<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    protected static function booted()
    {
        static::addGlobalScope('ADMIN', function ($builder) {
            $builder->where('role', 'ADMIN');
        });
    }
    //methode
    public function createDoctor()
    {

    }
    public function createSecretary()
    {

    }
    public function createPatient()
    {

    }
    public function viewGlobalStats()
    {

    }


}
