<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre'        => fake()->word(),
            'descripcion'   => fake()->realText(20),
            'codigo'        => fake()->randomElement(['VN', 'VL', 'VP', 'VLXL']),
            'precio'        => fake()->randomFloat(1, 20, 30),
        ];
    }
}
