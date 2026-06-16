<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 3 roles
        $roles = [
            'admin', // Admin quien gestiona todo
            'monitor', // Monitor quien imparte los entrenos
            'cliente' // Quien reserva las pistas para jugar, o apuntarse a entrenos
        ];

        // Crear Roles
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
