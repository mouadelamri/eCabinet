<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@ecabinet.com',
            'password' => bcrypt('password'),
            'role' => 'ADMIN',
        ]);

        User::factory()->create([
            'name' => 'Doctor Test',
            'email' => 'doctor@ecabinet.com',
            'password' => bcrypt('password'),
            'role' => 'DOCTOR',
        ]);

        User::factory()->create([
            'name' => 'Patient Test',
            'email' => 'patient@ecabinet.com',
            'password' => bcrypt('password'),
            'role' => 'PATIENT',
        ]);
    }
}
