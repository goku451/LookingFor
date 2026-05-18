<?php

namespace Database\Factories;

use App\Models\Objeto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Objeto>
 */
class ObjetoFactory extends Factory
{
    protected $model = Objeto::class;

    public function definition(): array
    {
        return [
            'nombre'      => fake()->randomElement([
                'Mochila negra', 'Celular Samsung', 'Cuaderno azul',
                'Llaves con llavero', 'Laptop HP', 'Cartera café',
                'Calculadora científica', 'Audífonos blancos',
                'Tablet iPad', 'Libro de matemáticas',
            ]),
            'tipo'        => fake()->randomElement(['Personal', 'Material de Estudio', 'Tecnológico']),
            'fecha'       => fake()->dateTimeBetween('-3 months', 'now'),
            'hora'        => fake()->time('H:i'),
            'lugar'       => fake()->randomElement([
                'Edificio A - Aula 101', 'Biblioteca Central', 'Cafetería',
                'Laboratorio de Cómputo', 'Canchas deportivas', 'Auditorio',
                'Pasillo principal', 'Estacionamiento', 'Edificio B - Aula 205',
            ]),
            'descripcion' => fake()->paragraph(2),
            'imagen'      => null,
            'user_id'     => User::factory(),
        ];
    }

    /**
     * Estado: objeto de tipo Personal.
     */
    public function personal(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'Personal',
        ]);
    }

    /**
     * Estado: objeto de tipo Material de Estudio.
     */
    public function materialEstudio(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'Material de Estudio',
        ]);
    }

    /**
     * Estado: objeto de tipo Tecnológico.
     */
    public function tecnologico(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'Tecnológico',
        ]);
    }
}
