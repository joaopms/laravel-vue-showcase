<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\User;
use App\TimeOfDay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'symptoms' => fake()->text(),
            'preferred_date' => fake()->dateTimeThisMonth('+15 days'),
            'preferred_time' => fake()->randomElement(TimeOfDay::class),
            'animal_age_months' => fake()->randomNumber(2),
            'animal_id' => Animal::factory(),
        ];
    }

    public function assigned(): self
    {
        return $this->state(function () {
            return [
                'receptionist_id' => User::factory()->receptionist(),
                'medic_id' => User::factory()->medic(),
                'assigned_at' => now(),
            ];
        });
    }
}
