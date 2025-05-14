<?php

use App\Models\Client;
use App\TimeOfDay;
use Tests\TestCase;
use Tests\TestData\AppointmentSchedule;

$VALID_APPOINTMENT = AppointmentSchedule::valid();

function scheduleAppointment(TestCase $self, array $data): void
{
    // Try to schedule an appointment
    $response = $self
        ->post(route('public.schedule-appointment'), $data);

    // Check for redirect. Since we're using Inertia, it redirects the user to the homepage on success
    $response->assertRedirect(route('home'));
}

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

    scheduleAppointment($this, $data);

    // Check if data was saved
    $this->assertDatabaseHas('clients', $data['client']);
    $this->assertDatabaseHas(
        'animals',
        Arr::except($data['animal'], ['age_years', 'age_months']),
    );
    $this->assertDatabaseHas('appointments', [
        ...$data['appointment'],
        'preferred_date' => $data['appointment']['preferred_date'],
        'preferred_time' => TimeOfDay::fromInputData($data['appointment']['preferred_time']),
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

    scheduleAppointment($this, $data);

    // Check if the client was updated
    $this->assertDatabaseHas('clients', $data['client']);
    $this->assertDatabaseCount('clients', 1);
});

test('new appointment does not recreate existing client and animal', function () use ($VALID_APPOINTMENT) {
    $data = $VALID_APPOINTMENT;

    // Try to schedule an appointment, twice, with the same data
    for ($i = 0; $i < 2; $i++) {
        scheduleAppointment($this, $data);
    }

    // Check if the client was updated
    $this->assertDatabaseCount('clients', 1);
    $this->assertDatabaseCount('animals', 1);
    $this->assertDatabaseCount('appointments', 2);
});
