<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
{
    const NAMES = [
        'Tico',
        'Luna',
        'Bobby',
        'Nina',
        'Simba',
        'Pantufa',
        'Kiko',
        'Becas',
        'Tobias',
        'Mia',
        'Zuca',
        'Sol',
        'Pipoca',
        'Farrusco',
        'Bola',
        'Gaspar',
        'Tita',
        'Max',
        'Nico',
        'Lili',
        'Mel',
        'Thor',
        'Belinha',
        'Fred',
        'Pitoco',
    ];

    const TYPES = ['Cão', 'Gato', 'Coelho', 'Hamster', 'Periquito'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(self::NAMES),
            'type' => fake()->randomElement(self::TYPES),
            'client_id' => Client::factory(),
        ];
    }
}
