<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'apellidos' => 'Bomberos',
            'compania' => 'Central',
            'dni' => '12345678A',
            'is_admin' => true,
            'email' => 'admin@bomberos.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Usuario',
            'apellidos' => 'Regular',
            'compania' => 'Norte',
            'dni' => '87654321B',
            'is_admin' => false,
            'email' => 'usuario@bomberos.com',
            'password' => Hash::make('password123'),
        ]);
    }
}