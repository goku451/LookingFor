<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre'            => fake()->name(),
            'codigo'            => fake()->unique()->numerify('USR####'),
            'email'             => fake()->unique()->safeEmail(),
            'password'          => static::$password ??= Hash::make('password'),
            'telefono'          => fake()->numerify('####-####'),
            'nivel'             => fake()->randomElement(['Básica', 'Media', 'Superior']),
            'grado'             => fake()->randomElement(['1°', '2°', '3°', '4°', '5°']),
            'seccion'           => fake()->randomElement(['A', 'B', 'C', 'D']),
            'foto'              => null,
            'role_id'           => Role::where('slug', 'alumno')->first()?->id ?? 1,
            'email_verified_at' => now(),
            'remember_token'    => Str::random(10),
        ];
    }

    /**
     * Estado: usuario con rol alumno.
     */
    public function alumno(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('slug', 'alumno')->first()?->id ?? 1,
        ]);
    }

    /**
     * Estado: usuario con rol profesor.
     */
    public function profesor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('slug', 'profesor')->first()?->id ?? 2,
        ]);
    }

    /**
     * Estado: usuario con rol administrador.
     */
    public function administrador(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('slug', 'administrador')->first()?->id ?? 3,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
