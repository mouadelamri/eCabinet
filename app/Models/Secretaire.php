<?php

namespace App\Models;

use App\Models\User;

class Secretaire extends User
{
    protected static function booted()
    {
        static::addGlobalScope('SECRETARY', function ($builder) {
            $builder->where('role', '
            SECRETARY');
        });
    }

}
