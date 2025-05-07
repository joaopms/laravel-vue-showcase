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
        'Buddy',
        'Luna',
        'Charlie',
        'Bella',
        'Max',
        'Daisy',
        'Milo',
        'Lola',
        'Rocky',
        'Coco',
        'Oscar',
        'Rosie',
        'Teddy',
        'Ruby',
        'Archie',
        'Molly',
        'Leo',
        'Poppy',
        'Jack',
        'Millie',
        'Bailey',
        'Sadie',
        'Zoey',
        'Bentley',
        'Riley',
    ];

    const TYPES = [
        'Cat',
        'Dog',
        'Rabbit',
        'Hamster',
        'Parakeet',
        'Guinea Pig',
        'Turtle',
        'Ferret',
        'Canary',
    ];

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
