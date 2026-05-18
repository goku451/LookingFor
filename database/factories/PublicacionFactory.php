<?php

namespace Database\Factories;

use App\Models\Publicacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Publicacion>
 */
class PublicacionFactory extends Factory
{
    protected $model = Publicacion::class;

    public function definition(): array
    {
        return [
            'fecha'       => fake()->dateTimeBetween('-3 months', 'now'),
            'comentarios' => fake()->paragraph(3),
            'user_id'     => User::factory(),
        ];
    }
}
