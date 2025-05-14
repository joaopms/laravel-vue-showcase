<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentSchedule;
use App\Models\Animal;
use App\Models\Client;
use App\TimeOfDay;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class PublicController extends Controller
{
    public function home()
    {
        return Inertia::render('client/ScheduleAppointment', [
            // TODO Cache animal types
            'animalTypes' => Animal::types(approved: true),
            'timesOfDay' => TimeOfDay::selectable(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function scheduleAppointment(StoreAppointmentSchedule $appSched)
    {
        DB::transaction(function () use ($appSched) {
            // Create or update the client name if the email matches an existing one
            $data = $appSched->safe();
            $client = Client::updateOrCreate(
                ['email' => $data['client.email']],
                $data['client']
            );

            // Get or create the animal
            $animal = $client->animals()->firstOrCreate($appSched->getAnimalData());

            // Create the appointment
            $animal->appointments()->create($appSched->getAppointmentData());
        });

        return to_route('home');
    }
}
