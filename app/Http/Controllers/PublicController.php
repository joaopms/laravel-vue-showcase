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
            // TODO Only show approved (aka assigned appointments) animals
            'animalTypes' => Animal::distinct('type')->orderBy('type')->pluck('type'),
            'timeOfDay' => TimeOfDay::selectable(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function scheduleAppointment(StoreAppointmentSchedule $appSched)
    {
        DB::transaction(function () use ($appSched) {
            // Create or update the client name if the email matches an existing one
            $client = Client::updateOrCreate(
                ['email' => $appSched['client.email']],
                $appSched['client']
            );

            // Get or create the animal
            $animal = $client->animals()->firstOrCreate($appSched->getAnimalData());

            // Create the appointment
            $animal->appointments()->create($appSched->getAppointmentData());
        });

        return to_route('public.home');
    }
}
