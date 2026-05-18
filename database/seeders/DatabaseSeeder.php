<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Admin por defecto
        \App\Models\User::create([
            'nombre'  => 'Administrador Principal',
            'codigo'  => 'ADMIN001',
            'email'   => 'admin@lookingfor.com',
            'password' => bcrypt('Admin123!'),
            'role_id' => \App\Models\Role::where('slug', 'administrador')->first()->id,
        ]);
    }
}
