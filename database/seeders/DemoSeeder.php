<?php

namespace Database\Seeders;

use App\Models\Objeto;
use App\Models\Publicacion;
use App\Models\Reporte;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Poblar la base de datos con datos de prueba.
     * Ejecutar: php artisan db:seed --class=DemoSeeder
     */
    public function run(): void
    {
        $this->command->info('Creando usuarios de prueba...');

        // Crear 5 alumnos
        $alumnos = User::factory()->count(5)->alumno()->create();

        // Crear 2 profesores
        $profesores = User::factory()->count(2)->profesor()->create();

        $todosUsuarios = $alumnos->merge($profesores);

        $this->command->info('Creando objetos perdidos...');

        // Crear 15 objetos perdidos distribuidos entre usuarios
        foreach ($todosUsuarios as $user) {
            Objeto::factory()->count(rand(1, 3))->create([
                'user_id' => $user->id,
            ]);
        }

        $this->command->info('Creando reportes de objetos encontrados...');

        // Crear 10 reportes distribuidos entre usuarios
        foreach ($todosUsuarios as $user) {
            Reporte::factory()->count(rand(1, 2))->create([
                'user_id' => $user->id,
            ]);
        }

        $this->command->info('Creando publicaciones...');

        // Crear 10 publicaciones distribuidas
        foreach ($todosUsuarios as $user) {
            Publicacion::factory()->count(rand(1, 2))->create([
                'user_id' => $user->id,
            ]);
        }

        $this->command->info('✅ Datos de prueba creados exitosamente.');
        $this->command->info('   - Alumnos: 5');
        $this->command->info('   - Profesores: 2');
        $this->command->info('   - Objetos, reportes y publicaciones distribuidos.');
    }
}
