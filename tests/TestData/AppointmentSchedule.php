<?php

namespace Tests\TestData;

use App\TimeOfDay;
use Database\Factories\AnimalFactory;
use Tests\TestCase;

class AppointmentSchedule
{
    public static function valid(): array
    {
        return [
            'client' => [
                'name' => fake()->name(),
                'email' => fake()->email(),
            ],
            'animal' => [
                'name' => fake()->randomElement(AnimalFactory::NAMES),
                'type' => fake()->randomElement(AnimalFactory::TYPES),
                'age_years' => fake()->numberBetween(1, 20),
                'age_months' => fake()->numberBetween(0, 12),
            ],
            'appointment' => [
                'preferred_date' => fake()->dateTimeInInterval(now(), '+30 days')->format(TestCase::DATE_FORMAT),
                'preferred_time' => fake()->randomElements(TimeOfDay::selectableStrings(), fake()->numberBetween(1, 2)),
                'symptoms' => fake()->realText(),
            ],
        ];
    }
}
