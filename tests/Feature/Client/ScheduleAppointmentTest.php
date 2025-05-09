<?php

use App\Models\Client;
use App\TimeOfDay;
use Database\Factories\AnimalFactory;

$VALID_APPOINTMENT = [
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

test('can not empty schedule appointment', function () {
    $response = $this
        ->post(route('public.schedule-appointment'));

    $response->assertSessionHasErrors();

    $this->assertDatabaseEmpty('clients');
    $this->assertDatabaseEmpty('animals');
    $this->assertDatabaseEmpty('appointments');
});

test('can schedule appointment', function () use ($VALID_APPOINTMENT) {
    $data = $VALID_APPOINTMENT;

    // Try to schedule an appointment
    $response = $this
        ->post(route('public.schedule-appointment'), $data);

    // Check for redirect. Since we're using Inertia, it redirects the user to the homepage on success
    $response->assertRedirect(route('public.schedule-appointment'));

    // Check if data was saved
    $this->assertDatabaseHas('clients', $data['client']);
    $this->assertDatabaseHas(
        'animals',
        Arr::except($data['animal'], ['age_years', 'age_months']),
    );
    $this->assertDatabaseHas('appointments', [
        ...$data['appointment'],
        'animal_age_months' => ($data['animal']['age_years'] * 12) + $data['animal']['age_months'],
    ]);
});

test('new appointment updates client name', function () use ($VALID_APPOINTMENT) {
    $data = $VALID_APPOINTMENT;

    // Create the client
    $clientData = ['name' => 'old name', 'email' => $data['client']['email']];
    Client::factory()
        ->state($clientData)
        ->create();

    // Ensure it exists
    $this->assertDatabaseHas('clients', $clientData);

    // Try to schedule an appointment
    $response = $this
        ->post(route('public.schedule-appointment'), $data);

    // Check for redirect. Since we're using Inertia, it redirects the user to the homepage on success
    $response->assertRedirect(route('public.schedule-appointment'));

    // Check if the client was updated
    $this->assertDatabaseHas('clients', $data['client']);
    $this->assertDatabaseCount('clients', 1);
});

test('new appointment does not recreate existing client and animal', function () use ($VALID_APPOINTMENT) {
    $data = $VALID_APPOINTMENT;

    // Try to schedule an appointment, twice, with the same data
    for ($i = 0; $i < 2; $i++) {
        $response = $this
            ->post(route('public.schedule-appointment'), $data);

        // Check for redirect. Since we're using Inertia, it redirects the user to the homepage on success
        $response->assertRedirect(route('public.schedule-appointment'));
    }

    // Check if the client was updated
    $this->assertDatabaseCount('clients', 1);
    $this->assertDatabaseCount('animals', 1);
    $this->assertDatabaseCount('appointments', 2);
});
