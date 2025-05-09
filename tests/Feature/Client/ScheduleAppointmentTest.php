<?php

use App\TimeOfDay;
use Database\Factories\AnimalFactory;

// TODO Update client name

test('can not empty schedule appointment', function () {
    $response = $this
        ->post(route('public.schedule-appointment'));

    $response->assertSessionHasErrors();

    $this->assertDatabaseEmpty('clients');
    $this->assertDatabaseEmpty('animals');
    $this->assertDatabaseEmpty('appointments');
});

test('can schedule appointment', function () {
    $data = [
        'client' => [
            'name' => fake()->name,
            'email' => fake()->email,
        ],
        'animal' => [
            'name' => fake()->randomElement(AnimalFactory::NAMES),
            'type' => fake()->randomElement(AnimalFactory::TYPES),
            'age_years' => fake()->numberBetween(1, 20),
            'age_months' => fake()->numberBetween(0, 12),
        ],
        'appointment' => [
            'preferred_date' => fake()->dateTimeInInterval(now(), '+30 days')->format('Y-m-d'),
            'preferred_time' => [fake()->randomElement(TimeOfDay::selectable())->value],
            'symptoms' => fake()->realText(),
        ],
    ];

    $response = $this
        ->post(route('public.schedule-appointment'), $data);

    $response->assertRedirect(route('public.schedule-appointment'));

    $ageInMonths = ($data['animal']['age_years'] * 12) + $data['animal']['age_months'];
    $this->assertDatabaseHas('clients', $data['client']);
    $this->assertDatabaseHas(
        'animals',
        Arr::except($data['animal'], ['age_years', 'age_months']),
    );
    $this->assertDatabaseHas('appointments', [
        ...$data['appointment'],
        'animal_age_months' => $ageInMonths,
    ]);
});
