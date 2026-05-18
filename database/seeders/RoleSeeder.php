<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Alumno',        'slug' => 'alumno'],
            ['nombre' => 'Profesor',       'slug' => 'profesor'],
            ['nombre' => 'Administrador',  'slug' => 'administrador'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
