<?php

use App\TimeOfDay;
use Carbon\Carbon;
use Laravel\Dusk\Browser;
use Tests\TestCase;
use Tests\TestData\AppointmentSchedule;

$VALID_APPOINTMENT = AppointmentSchedule::valid();

test('can visit page', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->assertSee(config('app.name'));
    });
});

test('can schedule appointment', function () use ($VALID_APPOINTMENT) {
    $data = collect($VALID_APPOINTMENT);
    $data_dot = $data->dot();
    $this->browse(function (Browser $browser) use ($data, $data_dot) {
        $browser->visit('/');

        // Type fields that can be typed
        // Use the collection and except before flattening to dot notation so appointment.preferred_time does not get flattened as well
        $data->except(['animal.type', 'appointment.preferred_date', 'appointment.preferred_time'])
            ->dot()
            ->each(fn ($value, $key) => $browser->type($key, $value));

        // Manually set the value of fields like date (depends on the browser format)
        $data_dot->only(['appointment.preferred_date'])
            ->each(function ($value, $key) use ($browser) {
                $browser->type($key, Carbon::createFromFormat(TestCase::DATE_FORMAT, $value)->format('mdY'));
            });

        // Manually type data in select inputs
        $data_dot->only(['animal.type'])
            ->each(function ($value, $key) use ($browser) {
                $browser->keys("[data-input-field-name='$key']",
                    $value,
                    '{enter}',
                );
            });

        // Check checkboxes
        collect([
            ...Arr::map(
                $data['appointment']['preferred_time'],
                fn (string $value) => TimeOfDay::from($value)->name
            ),
        ])
            ->each(function ($label) use ($browser) {
                $browser->click("[aria-label='$label']");
            });

        // Submit
        $browser->click('button[type=submit]')
            ->waitForText('Success');
    });

    $this->assertDatabaseCount('clients', 1);
    $this->assertDatabaseCount('animals', 1);
    $this->assertDatabaseCount('appointments', 1);
});
