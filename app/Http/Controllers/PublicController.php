<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentSchedule;
use App\Models\Animal;
use App\TimeOfDay;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function home()
    {
        return Inertia::render('client/NewAppointment', [
            // TODO Cache animal types
            'animalTypes' => Animal::distinct('type')->orderBy('type')->pluck('type'),
            'timeOfDay' => TimeOfDay::selectable(),
        ]);
    }

    public function scheduleAppointment(StoreAppointmentSchedule $appointmentSchedule)
    {
        dd($appointmentSchedule);
    }
}
