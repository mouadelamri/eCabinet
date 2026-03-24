<?php

namespace App\Models;

use App\Models\User;

class Admin extends User
{
    protected static function booted()
    {
        static::addGlobalScope('ADMIN', function ($builder) {
            $builder->where('role', 'ADMIN');
        });
    }
}
