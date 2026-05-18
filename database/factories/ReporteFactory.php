<?php

namespace Database\Factories;

use App\Models\Reporte;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reporte>
 */
class ReporteFactory extends Factory
{
    protected $model = Reporte::class;

    public function definition(): array
    {
        return [
            'nombre_reportante' => fake()->name(),
            'codigo_reportante' => fake()->numerify('ALU####'),
            'nivel'             => fake()->randomElement(['Básica', 'Media', 'Superior']),
            'grado'             => fake()->randomElement(['1°', '2°', '3°', '4°', '5°']),
            'seccion'           => fake()->randomElement(['A', 'B', 'C', 'D']),
            'correo'            => fake()->safeEmail(),
            'telefono'          => fake()->numerify('####-####'),
            'nombre_objeto'     => fake()->randomElement([
                'Mochila', 'Celular', 'Cuaderno', 'Llaves',
                'Laptop', 'Cartera', 'Calculadora', 'Audífonos',
            ]),
            'tipo'              => fake()->randomElement(['Personal', 'Material de Estudio', 'Tecnológico']),
            'fecha'             => fake()->dateTimeBetween('-3 months', 'now'),
            'hora'              => fake()->time('H:i'),
            'lugar'             => fake()->randomElement([
                'Edificio A - Aula 101', 'Biblioteca Central', 'Cafetería',
                'Laboratorio de Cómputo', 'Canchas deportivas', 'Auditorio',
            ]),
            'descripcion'       => fake()->paragraph(2),
            'imagen'            => null,
            'user_id'           => User::factory(),
        ];
    }
}
